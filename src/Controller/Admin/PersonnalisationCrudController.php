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
    // Indique à EasyAdmin quelle entité est gérée par ce CRUD.
    public static function getEntityFqcn(): string
    {
        return Personnalisation::class;
    }

    // Configure les champs d'une option de personnalisation.
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
