<?php

namespace App\Controller;

use Doctrine\Persistence\ManagerRegistry;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use App\Entity\Dossier;
use App\Form\DossierType;
use App\Security\EmailVerifier;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bridge\Twig\Mime\TemplatedEmail;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Mime\Address;

class DossierController extends AbstractController
{
    private EmailVerifier $emailVerifier;

    public function __construct(EmailVerifier $emailVerifier)
    {
        $this->emailVerifier = $emailVerifier;
    }
    
    #[Route('/dossier/list', name: 'dossier')]
    public function folderList(ManagerRegistry $doctrine): Response
    {

        $dossiers = $doctrine->getRepository(Dossier::class);
        $dossier = $dossiers->findAll();

        return $this->render('dossier/index.html.twig', [
            'dossiers' => $dossier,
        ]);
    }
    #[Route('/dossier/email/{id}', name: 'folderEmail')]
    public function folderEmail(ManagerRegistry $doctrine, int $id, MailerInterface $mailer): Response
    {

        $dossier = $doctrine->getRepository(Dossier::class)->findOneBy( ['privateId' => $id]);
    
        $email = (new TemplatedEmail())
        ->from(new Address('foyeranimal@gmail.com', 'Foyer Animal'))
        ->to($dossier->getEmail())
        ->subject('Merci de valider votre email')
        ->htmlTemplate('dossier/email.html.twig')
        ->context([
            'privateId' => $dossier->getPrivateId()
        ])   
        ;

    $mailer->send($email);

        $this->addFlash('success', 'L\'email a bien été envoyé.');

        return $this->redirectToRoute('dossier');

    }
    #[Route('/dossier/form/{id}', name: 'folderForm')]
    public function folderForm(ManagerRegistry $doctrine, int $id, Request $request, EntityManagerInterface $entityManager): Response
    {

        $dossier = $doctrine->getRepository(Dossier::class)->findOneBy( ['privateId' => $id]);

        $form = $this->createForm(DossierType::class, $dossier);
        $form->handleRequest($request);

        //Si le formulaire de don est validé, l'utilisateur envoie un don
        if ($form->isSubmitted() && $form->isValid()) {
            
            $cni = $form->get('cni')->getData();
            dump($cni);
            $cniName = md5(uniqid()).'.'. $cni->guessExtension();

            $cni->move(
                // $this->getParameter permet de récupérer la valeur d'un paramètre définit dans le fichier
                // de config services.yaml
                $this->getParameter('upload_file'),
                $cniName
            );
            $dossier->setCni($cniName);

            $pdf = $form->get('pdf')->getData();
            dump($pdf);
            $pdfName = md5(uniqid()).'.'. $pdf->guessExtension();

            $pdf->move(
                $this->getParameter('upload_file'),
                $pdfName
            );
            $dossier->setPdf($pdfName);
            $dossier->setStatut('DONE');

            $entityManager->persist($dossier);
            $entityManager->flush();

        
            $this->addFlash('success', 'Votre dossier est désormais en attente de validation.');

            return $this->redirectToRoute('home');
        }

        return $this->render('dossier/form.html.twig', [
            'form' => $form->createView(),

        ]);
    }
}
