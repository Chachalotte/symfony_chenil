<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Security;
use Doctrine\ORM\EntityManagerInterface;

use App\Form\DonType;
use App\Entity\Don;
class HomeController extends AbstractController
{
    #[Route('/', name: 'home')]
    public function index(Security $security, EntityManagerInterface $entityManager): Response
    {
        $user = $security->getUser();
        $don = new Don();
        $form = $this->createForm(DonType::class, $don);

        if ($form->isSubmitted() && $form->isValid()) {
            
            // encode the plain password
            $don->setTotal($form->get('Total')->getData());
            $don->setUser($user('id'));
            var_dump($user);

            $entityManager->persist($don);
            $entityManager->flush();

        
            $this->addFlash('success', 'Votre don a bien été pris en compte. Merci pour votre soutien !');

            return $this->redirectToRoute('home');
        }

        return $this->renderForm('home/index.html.twig', [
            'controller_name' => 'HomeController',
            'form' => $form,
            'user' => $user
        ]);
    }
}
