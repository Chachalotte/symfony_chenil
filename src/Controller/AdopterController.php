<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use App\Form\ContactFormType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Routing\Annotation\Route;
use Doctrine\Persistence\ManagerRegistry;
use App\Entity\Animal;
use App\Entity\Contact;
use App\Entity\User;
use SymfonyCasts\Bundle\VerifyEmail\Exception\VerifyEmailExceptionInterface;
use App\Security\EmailVerifier;
use Symfony\Bridge\Twig\Mime\TemplatedEmail;
use Symfony\Component\Mime\Address;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Mime\Email;

class AdopterController extends AbstractController
{
    private EmailVerifier $emailVerifier;

    public function __construct(EmailVerifier $emailVerifier)
    {
        $this->emailVerifier = $emailVerifier;
    }

//=========================================================================================
//Page de la liste des animaux
//=========================================================================================
    #[Route('/adopter', name: 'app_adopter')]
    public function index(ManagerRegistry $doctrine): Response
    {
        $animals = $doctrine->getRepository(Animal::class);

        $animal = $animals->findAll();

     
        return $this->render('adopter/index.html.twig', [
            'animal' => $animal
        ]);
    }


//=========================================================================================
//Page d'un animal individuel
//=========================================================================================
    #[Route('/adopter/{id}', name: 'app_animal_select')]
    public function animalSelect(ManagerRegistry $doctrine, int $id, Request $request): Response
    {
        //Lister un animal spécifiquement par son id
        $animal = $doctrine->getRepository(Animal::class)->find($id);
        $URL = $request->getRequestUri();


        return $this->render('adopter/animal.html.twig', [
            'animal' => $animal,
            'URL' => $URL
        ]);


    }

//=========================================================================================
//Page de contact
//=========================================================================================
    #[Route('/adopter/{id}/contact', name: 'Contact')]
    public function Contact(Request $request, EntityManagerInterface $entityManager, ManagerRegistry $doctrine, int $id): Response
    {

        $user = $this->getUser();
        $userEmail = $doctrine->getRepository(User::class)->find($user->getEmail());
        $animal = $doctrine->getRepository(Animal::class)->find($id);

        //Création d'un nouvel object Contact
        $contact = new Contact();
        $form = $this->createForm(ContactFormType::class, $contact);
        $form->handleRequest($request);
        
        if ($form->isSubmitted() && $form->isValid()) 
        {
            //Récupération des données du formulaire
            $instEmail= $form->get('email');
            $instMessage= $form->get('message')->getData();

            //Inscription des données dans la BDD (table contact)
            $entityManager->persist($contact);
            $entityManager->flush();

            //Génération de l'email
            $this->emailVerifier->sendEmailContact('app_verify_email', $user,
            (new TemplatedEmail())
                ->from(new Address('foyeranimal@gmail.com', 'Foyer Animal'))
                ->to(new Address('foyeranimal@gmail.com'))
                //->to($user->getEmail())
                ->subject('Contact pour adoption')
                ->htmlTemplate('adopter/emailContact.html.twig')
                ->context([
                    'animal' => $animal,
                    'message' => $instMessage,
                ]));
                
            //Redirection sur la page d'accueil avec annonce en rouge que la demande a été prise en compte avec succès    
            $this->addFlash('success', 'Votre demande a bien été prise en compte. Nous vous enverrons un retour prochainement.');
            return $this->redirectToRoute('home');
        }

        //Render du formulaire de contact
        return $this->render('adopter/contact.html.twig', [
            'ContactForm' => $form->createView(),
            'animal' => $animal,
            'contact' => $contact
        ]);
    }
}
