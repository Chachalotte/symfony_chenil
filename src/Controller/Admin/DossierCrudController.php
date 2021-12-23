<?php

namespace App\Controller\Admin;

use App\Entity\Dossier;

use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Field\Field;
use EasyCorp\Bundle\EasyAdminBundle\Field\AssociationField;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Bridge\Twig\Mime\TemplatedEmail;
use Symfony\Component\Mime\Address;
use Symfony\Component\Mailer\MailerInterface;

class DossierCrudController extends AbstractCrudController


{
    public function __construct(MailerInterface $mailer)
    {
        $this->mailer = $mailer;
    }

    public static function getEntityFqcn(): string
    {
        return Dossier::class;
    }

    public function configureFields(string $pageName): iterable
    {
        return [
            AssociationField::new('User'),
            // Field::new('CNI'),
            // Field::new('PDF'),
            Field::new('Email'),
            Field::new('cni')->onlyOnIndex(),
            Field::new('pdf')->onlyOnIndex(),
            Field::new('privateId')->onlyOnIndex(),
            Field::new('isValid')->onlyOnIndex(),
            Field::new('statut')->onlyOnIndex(),

        ];
    }

    public int $id;
    public function createEntity(string $entityFqcn)
    {
        $dossier = new Dossier();
        $dossier->setPrivateId(rand(0,999999999999));
        $dossier->setStatut("begin");


        return $dossier;
    }


}
