<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;

class SecurityController extends AbstractController
{
    // Cette route affiche la page de connexion et laisse Symfony vérifier les identifiants.
    #[Route('/login', name: 'app_login')]

    public function login(

        AuthenticationUtils $authenticationUtils

    ): Response
    {
        // Si l'utilisateur est déjà connecté, on évite de lui montrer à nouveau le formulaire.
        if ($this->getUser()) {
            return $this->redirectToRoute(
                'app_accueil'
            );
        }

        // On renvoie au template le dernier email saisi et l'erreur éventuelle de connexion.
        return $this->render(

            'security/index.html.twig',

            [

                'last_username' =>

                    $authenticationUtils
                    ->getLastUsername(),

                'error' =>

                    $authenticationUtils
                    ->getLastAuthenticationError(),

            ]
        );
    }

    // Cette route déclenche la déconnexion, qui est ensuite prise en charge par le firewall Symfony.
    #[Route('/logout', name: 'app_logout')]

    public function logout(): void
    {
        // Le contenu de cette méthode n'est pas exécuté directement : Symfony intercepte la route.
        
    }
}
