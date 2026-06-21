<?php

namespace App\Controller\Admin;

use App\Entity\Commande;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Field\AssociationField;
use EasyCorp\Bundle\EasyAdminBundle\Field\ChoiceField;
use EasyCorp\Bundle\EasyAdminBundle\Field\DateField;
use EasyCorp\Bundle\EasyAdminBundle\Field\IdField;

class CommandeCrudController extends AbstractCrudController
{
    public static function getEntityFqcn(): string
    {
        return Commande::class;
    }

    public function configureFields(string $pageName): iterable
    {
        // Champs commande
        return [
            IdField::new('id')->hideOnForm(),
            DateField::new('dateCommande', 'Date commande'),
            ChoiceField::new('statut')->setChoices([
                'En attente' => 'En attente',
                'Payée' => 'Payée',
                'Annulée' => 'Annulée',
            ]),
            AssociationField::new('utilisateur'),
            AssociationField::new('personnalisation'),
            AssociationField::new('paiements')->hideOnForm(),
        ];
    }
}
