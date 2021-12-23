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
            ImageField::new('img')
            ->setBasePath('uploads/')
            ->setUploadDir('public/uploads')
            // ->setFormType(FileUploadType::class)
            ->setUploadedFileNamePattern('[randomhash].[extension]')
            ->setRequired(false),            
            TextField::new('genre'),

        ];
    }

    public function createEntity(string $entityFqcn)
    {
        $animal = new Animal();
        $animal->setDate(new \DateTime('@'.strtotime('now')));
        return $animal;
    }
    
}
