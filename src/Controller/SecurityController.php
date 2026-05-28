<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;

class SecurityController extends AbstractController
{
    // Route connexion
    #[Route('/login', name: 'app_login')]

    public function login(

        // Outil Symfony connexion
        AuthenticationUtils $authenticationUtils

    ): Response
    {
        // Vérifie si utilisateur déjà connecté

        if ($this->getUser()) {

            // Redirection accueil

            return $this->redirectToRoute(
                'app_accueil'
            );
        }

        // Affiche page connexion

        return $this->render(

            'security/index.html.twig',

            [

                // Dernier email utilisé
                'last_username' =>

                    $authenticationUtils
                    ->getLastUsername(),

                // Message erreur connexion
                'error' =>

                    $authenticationUtils
                    ->getLastAuthenticationError(),

            ]
        );
    }

    // Route déconnexion
    #[Route('/logout', name: 'app_logout')]

    public function logout(): void
    {
        // Symfony gère automatiquement logout
        
    }
}