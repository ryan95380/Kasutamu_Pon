<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;

class SecurityController extends AbstractController
{
    // Connexion
    #[Route('/login', name: 'app_login')]

    public function login(

        AuthenticationUtils $authenticationUtils

    ): Response
    {
        // Vérification utilisateur
        if ($this->getUser()) {
            return $this->redirectToRoute(
                'app_accueil'
            );
        }

        // Formulaire connexion
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

    // Déconnexion
    #[Route('/logout', name: 'app_logout')]

    public function logout(): void
    {
        // Gérée par Symfony
        
    }
}
