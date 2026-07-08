<?php

namespace App\Controller;

use App\Entity\Commande;
use App\Entity\Paiement;
use App\Entity\Personnalisation;
use App\Entity\Utilisateur;
use App\Repository\FigurineRepository;
use App\Repository\PersonnalisationRepository;
use Doctrine\ORM\EntityManagerInterface;
use Stripe\Checkout\Session;
use Stripe\Stripe;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;

final class PanierController extends AbstractController
{
    // Cette route affiche le panier de l'utilisateur, stocké dans sa session Symfony.
    #[Route('/panier', name: 'app_panier')]

    public function index(
        Request $request
    ): Response
    {
        // La session permet de conserver le panier sans encore créer de commande en base.
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

    // Cette route ajoute au panier la figurine choisie avec ses options de personnalisation.
    #[Route('/panier/add/{id}', name: 'panier_add')]

    public function add(

        int $id,

        Request $request,

        FigurineRepository $figurineRepository

    ): Response
    {
        // On récupère la session et la figurine correspondant à l'identifiant de l'URL.
        $session =
        $request->getSession();

        $figurine =
        $figurineRepository->find($id);

        if (!$figurine) {

            return $this->redirectToRoute(
                'app_panier'
            );

        }

        // Les choix visuels et le prix final sont transmis depuis la page de personnalisation.
        $image = $request->query->get(

            'image',

            $figurine->getImage()

        );

        $custom = $request->query->get(

            'custom',

            'Aucune'

        );

        $cheveux = $request->query->get('cheveux', 'Aucun');
        $vetement = $request->query->get('vetement', 'Aucun');
        $accessoire = $request->query->get('accessoire', 'Aucun');
        $supplement = $this->calculerSupplement($cheveux, $vetement, $accessoire);
        $prix = (int) $figurine->getPrixBase() + $supplement;

        $panier =
        $session->get('panier', []);

        // L'article est ajouté sous forme de tableau pour être facilement affiché dans Twig.
        $panier[] = [
            'nom' => $figurine->getNom(),
            'description' =>

                $figurine->getDescription(),
            'image' => $image,
            'custom' => $custom,
            'cheveux' => $cheveux,
            'vetement' => $vetement,
            'accessoire' => $accessoire,
            'supplement' => $supplement,
            'prix' => $prix,
            'quantite' => 1

        ];

        // Le panier modifié est réenregistré dans la session de l'utilisateur.
        $session->set('panier', $panier);

        return $this->redirectToRoute(

            'app_panier'

        );
    }

    // Cette route supprime un article précis du panier grâce à sa position dans le tableau.
    #[Route('/panier/remove/{index}', name: 'panier_remove')]

    public function remove(

        int $index,

        Request $request

    ): Response
    {
        // On recharge le panier depuis la session avant de retirer l'élément demandé.
        $session =
        $request->getSession();

        $panier =
        $session->get('panier', []);

        if (isset($panier[$index])) {
            unset($panier[$index]);

            // Le tableau est réindexé pour éviter des trous dans les positions du panier.
            $panier =
            array_values($panier);

        }

        $session->set('panier', $panier);

        return $this->redirectToRoute(

            'app_panier'

        );
    }

    // Cette route transforme le panier en session de paiement Stripe.
    #[Route('/panier/paiement', name: 'panier_paiement')]
    public function paiement(Request $request): Response
    {
        // On charge le panier depuis la session pour préparer les lignes de paiement.
        $session = $request->getSession();
        $panier = $session->get('panier', []);

        // Si le panier est vide, on empêche le paiement et on renvoie l'utilisateur au panier.
        if (empty($panier)) {
            $this->addFlash('panier_info', 'Votre panier est vide.');

            return $this->redirectToRoute('app_panier');
        }

        if (!$this->getUser()) {
            $this->addFlash(
                'panier_info',
                'Connectez-vous avant de valider votre commande.'
            );

            return $this->redirectToRoute('app_login');
        }

        // La clé secrète Stripe vient de l'environnement pour ne pas être écrite dans le code.
        $secretKey = $_ENV['STRIPE_SECRET_KEY'] ?? '';

        if ($secretKey === '') {
            $this->addFlash(
                'panier_info',
                'Stripe n\'est pas encore configuré. Ajoute STRIPE_SECRET_KEY dans .env.local.'
            );

            return $this->redirectToRoute('app_panier');
        }

        Stripe::setApiKey($secretKey);

        // Chaque article du panier devient une ligne de paiement compréhensible par Stripe.
        $lineItems = [];

        foreach ($panier as $item) {
            $quantite = (int) ($item['quantite'] ?? 1);
            $prix = (int) ($item['prix'] ?? 0);

            $lineItems[] = [
                'quantity' => max(1, $quantite),
                'price_data' => [
                    'currency' => 'eur',
                    // Stripe attend les montants en centimes, donc le prix en euros est multiplié par 100.
                    'unit_amount' => max(1, $prix) * 100,
                    'product_data' => [
                        'name' => $item['nom'] ?? 'Figurine Kasutamu Pon',
                        'description' => $item['custom'] ?? 'Personnalisation',
                    ],
                ],
            ];
        }

        try {
            // Stripe crée une page de paiement sécurisée avec les URLs de retour du site.
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
            // En cas d'erreur Stripe, l'utilisateur reste sur le site avec un message clair.
            $this->addFlash(
                'panier_info',
                'Stripe est configuré, mais la session de paiement n\'a pas pu être créée.'
            );

            return $this->redirectToRoute('app_panier');
        }

        // L'utilisateur est redirigé vers Stripe pour saisir ses informations bancaires.
        return $this->redirect($checkout->url);
    }

    // Cette route est appelée par Stripe après un paiement validé.
    #[Route('/panier/success', name: 'panier_success')]
    public function success(
        Request $request,
        EntityManagerInterface $entityManager,
        PersonnalisationRepository $personnalisationRepository
    ): Response
    {
        $session = $request->getSession();
        $panier = $session->get('panier', []);

        if (!empty($panier)) {
            $session->set('derniere_commande', $this->creerRecapCommande($panier));
            $this->enregistrerCommandesPayees(
                $panier,
                $entityManager,
                $personnalisationRepository
            );
        }

        // Le panier est vidé pour éviter de repayer les mêmes articles.
        $session->remove('panier');

        return $this->render('panier/success.html.twig', [
            'commande' => $session->get('derniere_commande'),
        ]);
    }

    // Cette route est appelée si l'utilisateur annule le paiement sur Stripe.
    #[Route('/panier/cancel', name: 'panier_cancel')]
    public function cancel(): Response
    {
        // Le panier reste conservé afin que l'utilisateur puisse réessayer plus tard.
        return $this->render('panier/cancel.html.twig');
    }

    // Cette route permet de revoir le récapitulatif juste après un paiement réussi.
    #[Route('/commande/derniere', name: 'commande_derniere')]
    public function derniereCommande(Request $request): Response
    {
        $commande = $request->getSession()->get('derniere_commande');

        if (!$commande) {
            $this->addFlash('panier_info', 'Aucune commande payée à afficher pour le moment.');

            return $this->redirectToRoute('app_panier');
        }

        return $this->render('panier/commande.html.twig', [
            'commande' => $commande,
        ]);
    }

    private function creerRecapCommande(array $panier): array
    {
        $total = 0;

        foreach ($panier as $item) {
            $quantite = (int) ($item['quantite'] ?? 1);
            $prix = (int) ($item['prix'] ?? 0);

            $total += max(1, $quantite) * max(0, $prix);
        }

        return [
            'numero' => date('YmdHis'),
            'date' => date('d/m/Y H:i'),
            'items' => $panier,
            'total' => $total,
        ];
    }

    private function calculerSupplement(string $cheveux, string $vetement, string $accessoire): int
    {
        $prixOptions = [
            'cheveux' => [
                'Hair 1' => 10,
                'Hair 2' => 10,
                'Hair 3' => 10,
                'Hair 4' => 10,
            ],
            'vetement' => [
                'Costume 1' => 20,
                'Costume 2' => 25,
                'Costume 3' => 30,
            ],
            'accessoire' => [
                'Chapeau' => 8,
            ],
        ];

        return ($prixOptions['cheveux'][$cheveux] ?? 0)
            + ($prixOptions['vetement'][$vetement] ?? 0)
            + ($prixOptions['accessoire'][$accessoire] ?? 0);
    }

    private function enregistrerCommandesPayees(
        array $panier,
        EntityManagerInterface $entityManager,
        PersonnalisationRepository $personnalisationRepository
    ): void {
        $user = $this->getUser();

        if (!$user instanceof Utilisateur) {
            return;
        }

        foreach ($panier as $item) {
            $personnalisation = $this->trouverPersonnalisationCommande(
                $item,
                $personnalisationRepository
            );

            if (!$personnalisation) {
                continue;
            }

            $quantite = max(1, (int) ($item['quantite'] ?? 1));
            $prix = max(0, (int) ($item['prix'] ?? 0));

            $commande = new Commande();
            $commande->setDateCommande(new \DateTime());
            $commande->setStatut('Payee');
            $commande->setUtilisateur($user);
            $commande->setPersonnalisation($personnalisation);

            $paiement = new Paiement();
            $paiement->setMontant($prix * $quantite);
            $paiement->setModePaiement('Stripe');
            $paiement->setCommande($commande);

            $entityManager->persist($commande);
            $entityManager->persist($paiement);
        }

        $entityManager->flush();
    }

    private function trouverPersonnalisationCommande(
        array $item,
        PersonnalisationRepository $personnalisationRepository
    ): ?Personnalisation {
        $nomsPossibles = [
            $this->nomPersonnalisationPourCommande($item['accessoire'] ?? 'Aucun'),
            $this->nomPersonnalisationPourCommande($item['vetement'] ?? 'Aucun'),
            $this->nomPersonnalisationPourCommande($item['cheveux'] ?? 'Aucun'),
        ];

        foreach ($nomsPossibles as $nom) {
            if (!$nom) {
                continue;
            }

            $personnalisation = $personnalisationRepository->findOneBy([
                'nom' => $nom,
            ]);

            if ($personnalisation) {
                return $personnalisation;
            }
        }

        return null;
    }

    private function nomPersonnalisationPourCommande(string $nom): ?string
    {
        return match ($nom) {
            'Hair 1', 'Hair 2', 'Hair 3', 'Hair 4' => 'Cheveux Moderne 1',
            'Costume 1' => 'Costume Premium',
            'Costume 2' => 'Armure Shogun',
            'Costume 3' => 'Armure Imperiale',
            'Chapeau' => 'Chapeau Traditionnel',
            default => null,
        };
    }
}
