<?php

namespace App\Controller\Admin;

use EasyCorp\Bundle\EasyAdminBundle\Config\Dashboard;
use EasyCorp\Bundle\EasyAdminBundle\Config\MenuItem;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractDashboardController;
use EasyCorp\Bundle\EasyAdminBundle\Router\AdminUrlGenerator;

use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use App\Entity\User;
use App\Entity\Animal;
use App\Entity\Dossier;
use App\Entity\Contact;
use App\Entity\Produit;

class DashboardController extends AbstractDashboardController
{
    #[Route('/admin', name: 'admin')]
    public function index(): Response
    {
        return $this->render('@EasyAdmin/page/content.html.twig');
    }

    public function configureDashboard(): Dashboard
    {
        return Dashboard::new()
            ->setTitle('SymfonyProjectIpssiOneWeek');
    }

    public function configureMenuItems(): iterable
    {
        return [

            //=========================================================================================
            //Sections à gauche du menu admin
            //=========================================================================================            
            MenuItem::section('Liste des demandes d\'adoption'),
            MenuItem::linkToCrud('Contact', 'fa fa-user', Contact::class),

            MenuItem::section('Dossier'),
            MenuItem::linkToCrud('Dossier', 'fa fa-user', Dossier::class),

            MenuItem::section('Utilisateur'),
            MenuItem::linkToCrud('Utilisateur', 'fa fa-file-text', User::class),

            MenuItem::section('Animal'),
            MenuItem::linkToCrud('Animal', 'fa fa-user', Animal::class),

            MenuItem::section('Produit'),
            MenuItem::linkToCrud('Produit', 'fa fa-user', Produit::class),
            MenuItem::linkToLogout('Déconnexion', 'fa fa-exit'),

        ];
    }
}
