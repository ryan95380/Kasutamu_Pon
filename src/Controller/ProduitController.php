<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

final class ProduitController extends AbstractController
{
    // Route page produit
    #[Route('/produit', name: 'app_produit')]

    public function index(): Response
    {
        // Affiche la page produit/index.html.twig

        return $this->render(

            'produit/index.html.twig',

            [

                // Variable envoyée à Twig
                'controller_name' => 'ProduitController',

            ]
        );
    }
}