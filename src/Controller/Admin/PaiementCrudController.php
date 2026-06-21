<?php

namespace App\Controller\Admin;

use App\Entity\Paiement;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Field\AssociationField;
use EasyCorp\Bundle\EasyAdminBundle\Field\ChoiceField;
use EasyCorp\Bundle\EasyAdminBundle\Field\IdField;
use EasyCorp\Bundle\EasyAdminBundle\Field\IntegerField;

class PaiementCrudController extends AbstractCrudController
{
    public static function getEntityFqcn(): string
    {
        return Paiement::class;
    }

    public function configureFields(string $pageName): iterable
    {
        // Champs paiement
        return [
            IdField::new('id')->hideOnForm(),
            IntegerField::new('montant'),
            ChoiceField::new('modePaiement', 'Mode de paiement')->setChoices([
                'Stripe' => 'Stripe',
                'Carte bancaire' => 'Carte bancaire',
            ]),
            AssociationField::new('commande'),
        ];
    }
}
