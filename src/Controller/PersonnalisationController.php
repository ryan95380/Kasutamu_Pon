<?php

namespace App\Controller;

use App\Repository\FigurineRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class PersonnalisationController extends AbstractController
{
    // Route personnalisation
    // {id} = récupère l'id dans l'URL

    #[Route(
        '/personnalisation/{id}',
        name: 'personnalisation'
    )]

    public function index(

        // ID figurine récupéré URL
        int $id,

        // Repository figurines
        FigurineRepository $figurineRepository

    ): Response
    {
        // Cherche figurine dans la base

        $figurine =
        $figurineRepository->find($id);

        // Vérifie si figurine existe

        if (!$figurine) {

            // Affiche erreur 404

            throw $this->createNotFoundException(

                'Figurine introuvable'

            );

        }

        // Affiche page personnalisation

        return $this->render(

            'personnalisation/index.html.twig',

            [

                // Envoie figurine à Twig
                'figurine' => $figurine

            ]
        );
    }
}