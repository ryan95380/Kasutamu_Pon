<?php

namespace App\Controller\Admin;

use App\Entity\Commande;
use App\Entity\Figurine;
use App\Entity\Paiement;
use App\Entity\Personnalisation;
use App\Entity\Utilisateur;
use App\Controller\Admin\CommandeCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Attribute\AdminDashboard;
use EasyCorp\Bundle\EasyAdminBundle\Config\Dashboard;
use EasyCorp\Bundle\EasyAdminBundle\Config\MenuItem;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractDashboardController;
use EasyCorp\Bundle\EasyAdminBundle\Router\AdminUrlGenerator;
use Symfony\Component\HttpFoundation\Response;

#[AdminDashboard(routePath: '/admin', routeName: 'admin')]
class DashboardController extends AbstractDashboardController
{
    // Ouvre l'administration directement sur la gestion des commandes.
    public function index(): Response
    {
        $adminUrlGenerator = $this->container->get(AdminUrlGenerator::class);

        $url = $adminUrlGenerator
            ->setController(CommandeCrudController::class)
            ->generateUrl();

        return $this->redirect($url);
    }

    // Définit le titre visible dans l'interface EasyAdmin.
    public function configureDashboard(): Dashboard
    {
        return Dashboard::new()
            ->setTitle('Kasutamu Pon - Admin');
    }

    // Déclare les entrées du menu d'administration.
    public function configureMenuItems(): iterable
    {
        yield MenuItem::linkToDashboard('Dashboard', 'fa fa-home');
        yield MenuItem::section('Gestion Boutique');
        yield MenuItem::linkToCrud('Figurines', 'fas fa-dragon', Figurine::class);
        yield MenuItem::linkToCrud('Personnalisations', 'fas fa-palette', Personnalisation::class);
        yield MenuItem::linkToCrud('Commandes', 'fas fa-shopping-cart', Commande::class);
        yield MenuItem::linkToCrud('Paiements', 'fas fa-credit-card', Paiement::class);
        yield MenuItem::section('Utilisateurs');
        yield MenuItem::linkToCrud('Utilisateurs', 'fas fa-users', Utilisateur::class);
    }
}
