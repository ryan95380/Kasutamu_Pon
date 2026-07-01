<?php

namespace App\Controller\Admin;

use App\Entity\Personnalisation;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Field\AssociationField;
use EasyCorp\Bundle\EasyAdminBundle\Field\IdField;
use EasyCorp\Bundle\EasyAdminBundle\Field\IntegerField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;

class PersonnalisationCrudController extends AbstractCrudController
{
    // Ce CRUD EasyAdmin est relié à l'entité Personnalisation.
    public static function getEntityFqcn(): string
    {
        return Personnalisation::class;
    }

    // Ces champs permettent de gérer les options proposées pour personnaliser une figurine.
    public function configureFields(string $pageName): iterable
    {
        return [
            IdField::new('id')->hideOnForm(),
            TextField::new('nom'),
            TextField::new('image'),
            IntegerField::new('prix'),
            AssociationField::new('figurine'),
        ];
    }
}
