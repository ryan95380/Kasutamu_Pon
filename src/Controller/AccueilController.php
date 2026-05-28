<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

final class AccueilController extends AbstractController
{
    // Route de la page accueil
    #[Route('/accueil', name: 'app_accueil')]

    public function index(): Response
    {
        // Retourne la vue accueil/index.html.twig

        return $this->render('accueil/index.html.twig', [

            // Variable envoyée à Twig
            'controller_name' => 'AccueilController',

        ]);
    }
}