<?php

namespace App\Controller\Admin;

use App\Entity\Dossier;
use App\Entity\User;

use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Field\Field;
use EasyCorp\Bundle\EasyAdminBundle\Field\AssociationField;


class DossierCrudController extends AbstractCrudController
{
    public static function getEntityFqcn(): string
    {
        return Dossier::class;
    }

    public function configureFields(string $pageName): iterable
    {
        return [
            AssociationField::new('User'),
            Field::new('CNI'),
            Field::new('PDF'),
            Field::new('isValid'),

        ];
    }
}
