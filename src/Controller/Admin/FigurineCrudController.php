<?php

namespace App\Controller\Admin;

use App\Entity\Figurine;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Field\IdField;
use EasyCorp\Bundle\EasyAdminBundle\Field\IntegerField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextareaField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;

class FigurineCrudController extends AbstractCrudController
{
    // Ce CRUD EasyAdmin est relié à l'entité Figurine.
    public static function getEntityFqcn(): string
    {
        return Figurine::class;
    }

    // Ces champs permettent à l'administrateur de gérer le catalogue des figurines.
    public function configureFields(string $pageName): iterable
    {
        return [
            IdField::new('id')->hideOnForm(),
            TextField::new('nom'),
            IntegerField::new('prixBase', 'Prix de base'),
            TextareaField::new('description'),
            TextField::new('image'),
        ];
    }
}
