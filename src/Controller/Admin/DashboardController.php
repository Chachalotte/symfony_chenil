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

            

            MenuItem::section('Dossier'),
            MenuItem::linkToCrud('Dossier', 'fa fa-user', Dossier::class),

            MenuItem::section('Utilisateur'),
            MenuItem::linkToCrud('Utilisateur', 'fa fa-file-text', User::class),

            MenuItem::section('Animal'),
            MenuItem::linkToCrud('Animal', 'fa fa-user', Animal::class),
            MenuItem::linkToLogout('Logout', 'fa fa-exit'),

        ];
    }
}
