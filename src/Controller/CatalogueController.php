<?php

namespace App\Controller;

use App\Repository\FigurineRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class CatalogueController extends AbstractController
{
    #[Route('/catalogue', name: 'catalogue')]
    public function index(FigurineRepository $figurineRepository): Response
    {
        $figurines = $figurineRepository->findAll();

        return $this->render('catalogue/index.html.twig', [
            'figurines' => $figurines,
        ]);
    }
}
