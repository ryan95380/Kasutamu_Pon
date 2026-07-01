<?php

namespace App\Controller;

use App\Repository\FigurineRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class PersonnalisationController extends AbstractController
{
    // Affiche l'écran de personnalisation d'une figurine.

    #[Route(
        '/personnalisation/{id}',
        name: 'personnalisation'
    )]

    public function index(

        int $id,
        FigurineRepository $figurineRepository

    ): Response
    {
        // Recherche la figurine sélectionnée par son identifiant.
        $figurine =
        $figurineRepository->find($id);

        // Arrête la page si la figurine n'existe pas.
        if (!$figurine) {
            throw $this->createNotFoundException(

                'Figurine introuvable'

            );

        }

        // Envoie la figurine au template de personnalisation.
        return $this->render(

            'personnalisation/index.html.twig',

            [

                'figurine' => $figurine

            ]
        );
    }
}
