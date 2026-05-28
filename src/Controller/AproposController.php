<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

final class AproposController extends AbstractController
{
    // Route page à propos
    #[Route('/apropos', name: 'app_apropos')]

    public function index(): Response
    {
        // Affiche la page apropos/index.html.twig

        return $this->render('apropos/index.html.twig', [

            // Variable envoyée à Twig
            'controller_name' => 'AproposController',

        ]);
    }
}