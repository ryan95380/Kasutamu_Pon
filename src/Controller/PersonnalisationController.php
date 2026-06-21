<?php

namespace App\Controller;

use App\Repository\FigurineRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class PersonnalisationController extends AbstractController
{
    // Personnalisation

    #[Route(
        '/personnalisation/{id}',
        name: 'personnalisation'
    )]

    public function index(

        int $id,
        FigurineRepository $figurineRepository

    ): Response
    {
        // Récupération figurine
        $figurine =
        $figurineRepository->find($id);

        // Vérification figurine
        if (!$figurine) {
            throw $this->createNotFoundException(

                'Figurine introuvable'

            );

        }

        return $this->render(

            'personnalisation/index.html.twig',

            [

                'figurine' => $figurine

            ]
        );
    }
}
