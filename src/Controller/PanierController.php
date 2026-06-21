<?php

namespace App\Controller;

use App\Repository\FigurineRepository;
use Stripe\Checkout\Session;
use Stripe\Stripe;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;

final class PanierController extends AbstractController
{
    // Panier
    #[Route('/panier', name: 'app_panier')]

    public function index(
        Request $request
    ): Response
    {
        // Panier session
        $session =
        $request->getSession();

        $panier =
        $session->get('panier', []);

        return $this->render(

            'panier/index.html.twig',

            [

                'panier' => $panier

            ]
        );
    }

    // Ajout panier
    #[Route('/panier/add/{id}', name: 'panier_add')]

    public function add(

        int $id,

        Request $request,

        FigurineRepository $repo

    ): Response
    {
        // Récupération figurine
        $session =
        $request->getSession();

        $figurine =
        $repo->find($id);

        if (!$figurine) {

            return $this->redirectToRoute(
                'app_panier'
            );

        }

        // Options personnalisation
        $image = $request->query->get(

            'image',

            $figurine->getImage()

        );

        $custom = $request->query->get(

            'custom',

            'Aucune'

        );

        $prix = $request->query->get(

            'prix',

            $figurine->getPrixBase()

        );

        $panier =
        $session->get('panier', []);

        $panier[] = [
            'nom' => $figurine->getNom(),
            'description' =>

                $figurine->getDescription(),
            'image' => $image,
            'custom' => $custom,
            'prix' => (int) $prix,
            'quantite' => 1

        ];

        // Sauvegarde session
        $session->set('panier', $panier);

        return $this->redirectToRoute(

            'app_panier'

        );
    }

    // Suppression panier
    #[Route('/panier/remove/{index}', name: 'panier_remove')]

    public function remove(

        int $index,

        Request $request

    ): Response
    {
        $session =
        $request->getSession();

        $panier =
        $session->get('panier', []);

        if (isset($panier[$index])) {
            unset($panier[$index]);

            $panier =
            array_values($panier);

        }

        $session->set('panier', $panier);

        return $this->redirectToRoute(

            'app_panier'

        );
    }

    // Paiement Stripe
    #[Route('/panier/paiement', name: 'panier_paiement')]
    public function paiement(Request $request): Response
    {
        // Panier session
        $session = $request->getSession();
        $panier = $session->get('panier', []);

        // Vérification panier
        if (empty($panier)) {
            $this->addFlash('panier_info', 'Votre panier est vide.');

            return $this->redirectToRoute('app_panier');
        }

        // Clé secrète Stripe
        $secretKey = $_ENV['STRIPE_SECRET_KEY'] ?? '';

        if ($secretKey === '') {
            $this->addFlash(
                'panier_info',
                'Stripe n\'est pas encore configuré. Ajoute STRIPE_SECRET_KEY dans .env.local.'
            );

            return $this->redirectToRoute('app_panier');
        }

        Stripe::setApiKey($secretKey);

        // Lignes de paiement
        $lineItems = [];

        foreach ($panier as $item) {
            $quantite = (int) ($item['quantite'] ?? 1);
            $prix = (int) ($item['prix'] ?? 0);

            $lineItems[] = [
                'quantity' => max(1, $quantite),
                'price_data' => [
                    'currency' => 'eur',
                    // Conversion en centimes
                    'unit_amount' => max(1, $prix) * 100,
                    'product_data' => [
                        'name' => $item['nom'] ?? 'Figurine Kasutamu Pon',
                        'description' => $item['custom'] ?? 'Personnalisation',
                    ],
                ],
            ];
        }

        try {
            // Session Stripe Checkout
            $checkout = Session::create([
                'mode' => 'payment',
                'payment_method_types' => ['card'],
                'line_items' => $lineItems,
                'success_url' => $this->generateUrl(
                    'panier_success',
                    [],
                    UrlGeneratorInterface::ABSOLUTE_URL
                ),
                'cancel_url' => $this->generateUrl(
                    'panier_cancel',
                    [],
                    UrlGeneratorInterface::ABSOLUTE_URL
                ),
            ]);
        } catch (\Throwable) {
            // Erreur Stripe
            $this->addFlash(
                'panier_info',
                'Stripe est configuré, mais la session de paiement n\'a pas pu être créée.'
            );

            return $this->redirectToRoute('app_panier');
        }

        // Redirection Stripe
        return $this->redirect($checkout->url);
    }

    #[Route('/panier/success', name: 'panier_success')]
    public function success(Request $request): Response
    {
        // Vidage du panier
        $request->getSession()->remove('panier');

        return $this->render('panier/success.html.twig');
    }

    #[Route('/panier/cancel', name: 'panier_cancel')]
    public function cancel(): Response
    {
        // Paiement annulé
        return $this->render('panier/cancel.html.twig');
    }
}
