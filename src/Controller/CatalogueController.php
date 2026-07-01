<?php

namespace App\Controller;

use App\Repository\FigurineRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class CatalogueController extends AbstractController
{
    // Cette route affiche le catalogue des figurines disponibles.
    #[Route('/catalogue', name: 'catalogue')]

    public function index(
        FigurineRepository $figurineRepository
    ): Response
    {
        // Le repository Doctrine récupère toutes les figurines enregistrées en base.
        $figurines =
        $figurineRepository->findAll();

        // Les données sont envoyées au template Twig pour construire les cartes du catalogue.
        return $this->render(
            'catalogue/index.html.twig',

            [

                'figurines' => $figurines,

            ]
        );
    }
}
