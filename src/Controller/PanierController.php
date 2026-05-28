<?php

namespace App\Controller;

use App\Repository\FigurineRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

final class PanierController extends AbstractController
{
    // Route page panier
    #[Route('/panier', name: 'app_panier')]

    public function index(
        Request $request
    ): Response
    {
        // Récupère la session utilisateur

        $session =
        $request->getSession();

        // Récupère le panier stocké dans la session

        $panier =
        $session->get('panier', []);

        // Affiche la page panier

        return $this->render(

            'panier/index.html.twig',

            [

                // Envoie panier à Twig
                'panier' => $panier

            ]
        );
    }

    // Route ajout panier
    #[Route('/panier/add/{id}', name: 'panier_add')]

    public function add(

        // ID produit
        int $id,

        Request $request,

        // Repository figurine
        FigurineRepository $repo

    ): Response
    {
        // Récupère session

        $session =
        $request->getSession();

        // Cherche figurine dans la base

        $figurine =
        $repo->find($id);

        // Vérifie si figurine existe

        if (!$figurine) {

            return $this->redirectToRoute(
                'app_panier'
            );

        }

        // Récupère image personnalisée

        $image = $request->query->get(

            'image',

            $figurine->getImage()

        );

        // Récupère nom personnalisation

        $custom = $request->query->get(

            'custom',

            'Aucune'

        );

        // Récupère prix final

        $prix = $request->query->get(

            'prix',

            $figurine->getPrixBase()

        );

        // Récupère panier actuel

        $panier =
        $session->get('panier', []);

        // Ajoute nouveau produit panier

        $panier[] = [

            // Nom produit
            'nom' => $figurine->getNom(),

            // Description produit
            'description' =>

                $figurine->getDescription(),

            // Image personnalisée
            'image' => $image,

            // Nom personnalisation
            'custom' => $custom,

            // Prix final
            'prix' => $prix

        ];

        // Sauvegarde panier session

        $session->set('panier', $panier);

        // Redirection panier

        return $this->redirectToRoute(

            'app_panier'

        );
    }

    // Route suppression produit panier
    #[Route('/panier/remove/{index}', name: 'panier_remove')]

    public function remove(

        // Index du produit
        int $index,

        Request $request

    ): Response
    {
        // Récupère session

        $session =
        $request->getSession();

        // Récupère panier

        $panier =
        $session->get('panier', []);

        // Vérifie si produit existe

        if (isset($panier[$index])) {

            // Supprime produit

            unset($panier[$index]);

            // Réorganise tableau

            $panier =
            array_values($panier);

        }

        // Sauvegarde panier

        $session->set('panier', $panier);

        // Retour panier

        return $this->redirectToRoute(

            'app_panier'

        );
    }
}