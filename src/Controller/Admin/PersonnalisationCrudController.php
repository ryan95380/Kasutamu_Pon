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
    public static function getEntityFqcn(): string
    {
        return Personnalisation::class;
    }

    public function configureFields(string $pageName): iterable
    {
        // Champs personnalisation
        return [
            IdField::new('id')->hideOnForm(),
            TextField::new('nom'),
            TextField::new('image'),
            IntegerField::new('prix'),
            AssociationField::new('figurine'),
        ];
    }
}
