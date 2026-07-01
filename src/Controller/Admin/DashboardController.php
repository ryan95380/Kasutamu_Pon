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
    // À l'ouverture de l'administration, on redirige directement vers la gestion des commandes.
    public function index(): Response
    {
        $adminUrlGenerator = $this->container->get(AdminUrlGenerator::class);

        $url = $adminUrlGenerator
            ->setController(CommandeCrudController::class)
            ->generateUrl();

        return $this->redirect($url);
    }

    // Cette méthode personnalise le titre affiché en haut du tableau de bord EasyAdmin.
    public function configureDashboard(): Dashboard
    {
        return Dashboard::new()
            ->setTitle('Kasutamu Pon - Admin');
    }

    // Le menu regroupe les principales parties que l'administrateur peut gérer.
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
