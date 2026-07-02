<?php

namespace App\Controller\Admin;

use App\Entity\Paiement;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Field\AssociationField;
use EasyCorp\Bundle\EasyAdminBundle\Field\ChoiceField;
use EasyCorp\Bundle\EasyAdminBundle\Field\IdField;
use EasyCorp\Bundle\EasyAdminBundle\Field\IntegerField;
use Symfony\Component\Security\Http\Attribute\IsGranted;

#[IsGranted('ROLE_ADMIN')]
class PaiementCrudController extends AbstractCrudController
{
    // Ce CRUD EasyAdmin est relié à l'entité Paiement.
    public static function getEntityFqcn(): string
    {
        return Paiement::class;
    }

    // Ces champs servent à suivre le montant, le mode de paiement et la commande associée.
    public function configureFields(string $pageName): iterable
    {
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
