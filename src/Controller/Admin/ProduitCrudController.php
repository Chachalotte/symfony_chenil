<?php

namespace App\Controller\Admin;

use App\Entity\Produit;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Field\ImageField;
use EasyCorp\Bundle\EasyAdminBundle\Field\IntegerField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;

class ProduitCrudController extends AbstractCrudController
{
    public static function getEntityFqcn(): string
    {
        return Produit::class;
    }

    public function configureFields(string $pageName): iterable
    {
        return [
            IntegerField::new('prix'),
            IntegerField::new('stock'),
            TextField::new('description'),
            //=========================================================================================
            //C'est grâce à ce champ que l'on peut insérer des images dans le dossier "uploads" et retourner le champ de texte en BDD
            //=========================================================================================
            ImageField::new('img')
            ->setBasePath('uploads/')
            ->setUploadDir('public/uploads')
            // ->setFormType(FileUploadType::class)
            ->setUploadedFileNamePattern('[randomhash].[extension]')
            ->setRequired(false),            
            TextField::new('titre'),
            TextField::new('categorie'),

        ];
    }

    public function createEntity(string $entityFqcn)
    {
        $produit = new Produit();
        return $produit;
    }
}
