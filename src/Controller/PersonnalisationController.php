<?php

namespace App\Controller;

use App\Repository\FigurineRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class PersonnalisationController extends AbstractController
{
    // Cette route affiche la personnalisation de la figurine choisie dans le catalogue.

    #[Route(
        '/personnalisation/{id}',
        name: 'personnalisation'
    )]

    public function index(

        int $id,
        FigurineRepository $figurineRepository

    ): Response
    {
        // L'identifiant présent dans l'URL permet de retrouver la bonne figurine en base.
        $figurine =
        $figurineRepository->find($id);

        // Si aucune figurine ne correspond, Symfony affiche une erreur 404 propre.
        if (!$figurine) {
            throw $this->createNotFoundException(

                'Figurine introuvable'

            );

        }

        // La figurine est envoyée au template pour afficher son image, son prix et ses options.
        return $this->render(

            'personnalisation/index.html.twig',

            [

                'figurine' => $figurine

            ]
        );
    }
}
