<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

final class PersonnalisationController extends AbstractController
{
    #[Route('/personnalisation', name: 'app_personnalisation')]
    public function index(): Response
    {
        return $this->render('personnalisation/index.html.twig', [
            'controller_name' => 'PersonnalisationController',
        ]);
    }
}
