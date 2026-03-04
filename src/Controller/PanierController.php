<?php

namespace App\Controller;

use App\Repository\FigurineRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

final class PanierController extends AbstractController
{
    #[Route('/panier', name: 'app_panier')]
    public function index(Request $request): Response
    {
        $session = $request->getSession();
        $panier = $session->get('panier', []);

        return $this->render('panier/index.html.twig', [
            'panier' => $panier
        ]);
    }

    #[Route('/panier/add/{id}', name: 'panier_add')]
    public function add(int $id, Request $request, FigurineRepository $repo): Response
    {
        $session = $request->getSession();

        $figurine = $repo->find($id);

        if (!$figurine) {
            return $this->redirectToRoute('app_panier');
        }

        $panier = $session->get('panier', []);

        $panier[] = [
            'nom' => $figurine->getNom(),
            'description' => $figurine->getDescription(),
            'image' => $figurine->getImage(),
            'prix' => $figurine->getPrixBase()
        ];

        $session->set('panier', $panier);

        return $this->redirectToRoute('app_panier');
    }

    #[Route('/panier/remove/{index}', name: 'panier_remove')]
    public function remove(int $index, Request $request): Response
    {
        $session = $request->getSession();
        $panier = $session->get('panier', []);

        if (isset($panier[$index])) {
            unset($panier[$index]);
            $panier = array_values($panier);
        }

        $session->set('panier', $panier);

        return $this->redirectToRoute('app_panier');
    }
}
