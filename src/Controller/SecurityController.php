<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;

class SecurityController extends AbstractController
{
    // Affiche le formulaire de connexion de l'utilisateur.
    #[Route('/login', name: 'app_login')]

    public function login(

        AuthenticationUtils $authenticationUtils

    ): Response
    {
        // Redirige un utilisateur déjà connecté vers l'accueil.
        if ($this->getUser()) {
            return $this->redirectToRoute(
                'app_accueil'
            );
        }

        // Envoie les erreurs de connexion au template Twig.
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

    // Laisse Symfony Security gérer la déconnexion.
    #[Route('/logout', name: 'app_logout')]

    public function logout(): void
    {
        // Cette méthode est interceptée par le firewall Symfony.
        
    }
}
