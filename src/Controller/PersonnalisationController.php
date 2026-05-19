<?php

namespace App\Controller;

use App\Repository\FigurineRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class PersonnalisationController extends AbstractController
{
    #[Route('/personnalisation/{id}', name: 'personnalisation')]
    public function index(
        int $id,
        FigurineRepository $figurineRepository
    ): Response
    {
        $figurine = $figurineRepository->find($id);

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