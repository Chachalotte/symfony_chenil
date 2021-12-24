<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Doctrine\Persistence\ManagerRegistry;
use App\Form\DonType;
use App\Entity\Don;
use App\Entity\User;
use App\Entity\Animal;

class HomeController extends AbstractController
{
    //=========================================================================================
    //Page d'accueil qui contient la liste des animaux et le total des dons
    //=========================================================================================
    #[Route('/', name: 'home')]
    public function index(EntityManagerInterface $entityManager, Request $request, ManagerRegistry $doctrine): Response
    {
        //Liste des animaux
        $animals = $doctrine->getRepository(Animal::class);
        $animal = $animals->findAll();

        //=========================================================================================
        //Si l'utilisateur n'est pas connecté, pas de retour d'id de l'utilisateur
        //=========================================================================================
        $user = $this->getUser();
        if (!empty($user)){
            $userId = $doctrine->getRepository(User::class)->find($user->getId());
        }
        $don = new Don();
        $form = $this->createForm(DonType::class);
        $form->handleRequest($request);

        //=========================================================================================
        //ADDITION DES DONS POUR RETOURNER UN RESULTAT UNIQUE
        //=========================================================================================        
        $qb = $entityManager->createQueryBuilder();
        $qb = $qb
        ->select( 'SUM(e.Total) as totalRevenue' )
        ->from( 'App\Entity\Don', 'e' )
        ->getQuery();

        $dons = $qb->getOneOrNullResult();

        //Si le formulaire de don est validé, l'utilisateur envoie un don
        if ($form->isSubmitted() && $form->isValid()) {
            
            $don->setTotal($form->get('Total')->getData());
            $don->setUser($userId);
            $entityManager->persist($don);
            $entityManager->flush();

        
            $this->addFlash('success', 'Votre don a bien été pris en compte. Merci pour votre soutien !');

            return $this->redirectToRoute('home');
        }

        return $this->renderForm('home/index.html.twig', [
            'form' => $form,
            'user' => $user, 
            'dons' => $dons['totalRevenue'],
            'animals' => $animal
        ]);
    }
}
