<?php

namespace App\Controller;

use App\Repository\FigurineRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class CatalogueController extends AbstractController
{
    // Affiche la page catalogue.
    #[Route('/catalogue', name: 'catalogue')]

    public function index(
        FigurineRepository $figurineRepository
    ): Response
    {
        // Récupère toutes les figurines depuis la base de données.
        $figurines =
        $figurineRepository->findAll();

        // Envoie la liste des figurines au template Twig.
        return $this->render(
            'catalogue/index.html.twig',

            [

                'figurines' => $figurines,

            ]
        );
    }
}
