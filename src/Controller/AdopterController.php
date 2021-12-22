<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Doctrine\Persistence\ManagerRegistry;
use App\Entity\Animal;

class AdopterController extends AbstractController
{
    #[Route('/adopter', name: 'app_adopter')]
    public function index(ManagerRegistry $doctrine): Response
    {
        $animals = $doctrine->getRepository(Animal::class);

        $animal = $animals->findAll();

     
        return $this->render('adopter/index.html.twig', [
            'animal' => $animal
        ]);
    }

    #[Route('/adopter/{id}', name: 'app_animal_select')]
    public function animalSelect(ManagerRegistry $doctrine, int $id): Response
    {
        //Lister un animal spécifiquement par son id
        $animal = $doctrine->getRepository(Animal::class)->find($id);

        return $this->render('adopter/animal.html.twig', [
            'animal' => $animal,
        ]);
    }
}
