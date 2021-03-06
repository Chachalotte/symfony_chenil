<?php

namespace App\Controller\Admin;

use App\Entity\Animal;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Field\ImageField;
use EasyCorp\Bundle\EasyAdminBundle\Field\IntegerField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;

class AnimalCrudController extends AbstractCrudController
{
    public static function getEntityFqcn(): string
    {
        return Animal::class;
    }

    
    public function configureFields(string $pageName): iterable
    {
        return [
            IntegerField::new('age'),
            TextField::new('nom'),
            TextField::new('race'),
            IntegerField::new('taille'),
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
            TextField::new('genre'),

        ];
    }

    //=========================================================================================
    //On intéragit avec l'entité que l'on va crée pour lui insérer la date actuelle
    //=========================================================================================
    public function createEntity(string $entityFqcn)
    {
        $animal = new Animal();
        $animal->setDate(new \DateTime('@'.strtotime('now')));
        return $animal;
    }
    
}
