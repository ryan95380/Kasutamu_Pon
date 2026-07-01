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
    // Affiche le panier stocké dans la session.
    #[Route('/panier', name: 'app_panier')]

    public function index(
        Request $request
    ): Response
    {
        // Récupère le panier courant depuis la session utilisateur.
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

    // Ajoute une figurine personnalisée au panier.
    #[Route('/panier/add/{id}', name: 'panier_add')]

    public function add(

        int $id,

        Request $request,

        FigurineRepository $repo

    ): Response
    {
        // Récupère la session et la figurine demandée.
        $session =
        $request->getSession();

        $figurine =
        $repo->find($id);

        if (!$figurine) {

            return $this->redirectToRoute(
                'app_panier'
            );

        }

        // Récupère les choix de personnalisation envoyés dans l'URL.
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

        // Ajoute l'article personnalisé dans le tableau du panier.
        $panier[] = [
            'nom' => $figurine->getNom(),
            'description' =>

                $figurine->getDescription(),
            'image' => $image,
            'custom' => $custom,
            'prix' => (int) $prix,
            'quantite' => 1

        ];

        // Sauvegarde le panier mis à jour dans la session.
        $session->set('panier', $panier);

        return $this->redirectToRoute(

            'app_panier'

        );
    }

    // Supprime un article du panier grâce à son index.
    #[Route('/panier/remove/{index}', name: 'panier_remove')]

    public function remove(

        int $index,

        Request $request

    ): Response
    {
        // Récupère le panier avant de retirer l'article choisi.
        $session =
        $request->getSession();

        $panier =
        $session->get('panier', []);

        if (isset($panier[$index])) {
            unset($panier[$index]);

            // Réindexe le tableau pour garder des positions propres.
            $panier =
            array_values($panier);

        }

        $session->set('panier', $panier);

        return $this->redirectToRoute(

            'app_panier'

        );
    }

    // Crée une session de paiement Stripe pour le panier.
    #[Route('/panier/paiement', name: 'panier_paiement')]
    public function paiement(Request $request): Response
    {
        // Charge le panier depuis la session avant le paiement.
        $session = $request->getSession();
        $panier = $session->get('panier', []);

        // Bloque le paiement si le panier est vide.
        if (empty($panier)) {
            $this->addFlash('panier_info', 'Votre panier est vide.');

            return $this->redirectToRoute('app_panier');
        }

        // Récupère la clé secrète Stripe depuis l'environnement.
        $secretKey = $_ENV['STRIPE_SECRET_KEY'] ?? '';

        if ($secretKey === '') {
            $this->addFlash(
                'panier_info',
                'Stripe n\'est pas encore configuré. Ajoute STRIPE_SECRET_KEY dans .env.local.'
            );

            return $this->redirectToRoute('app_panier');
        }

        Stripe::setApiKey($secretKey);

        // Transforme les articles du panier en lignes Stripe Checkout.
        $lineItems = [];

        foreach ($panier as $item) {
            $quantite = (int) ($item['quantite'] ?? 1);
            $prix = (int) ($item['prix'] ?? 0);

            $lineItems[] = [
                'quantity' => max(1, $quantite),
                'price_data' => [
                    'currency' => 'eur',
                    // Stripe attend les montants en centimes.
                    'unit_amount' => max(1, $prix) * 100,
                    'product_data' => [
                        'name' => $item['nom'] ?? 'Figurine Kasutamu Pon',
                        'description' => $item['custom'] ?? 'Personnalisation',
                    ],
                ],
            ];
        }

        try {
            // Crée la session de paiement sécurisée chez Stripe.
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
            // Affiche un message si Stripe refuse la création de session.
            $this->addFlash(
                'panier_info',
                'Stripe est configuré, mais la session de paiement n\'a pas pu être créée.'
            );

            return $this->redirectToRoute('app_panier');
        }

        // Redirige l'utilisateur vers la page de paiement Stripe.
        return $this->redirect($checkout->url);
    }

    #[Route('/panier/success', name: 'panier_success')]
    public function success(Request $request): Response
    {
        // Vide le panier après un paiement valide.
        $request->getSession()->remove('panier');

        return $this->render('panier/success.html.twig');
    }

    #[Route('/panier/cancel', name: 'panier_cancel')]
    public function cancel(): Response
    {
        // Affiche la page de retour si le paiement est annulé.
        return $this->render('panier/cancel.html.twig');
    }
}
