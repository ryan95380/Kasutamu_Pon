<?php

namespace App\Controller;

use App\Entity\Utilisateur;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class InscriptionController extends AbstractController
{
    // Route page inscription
    #[Route('/inscription', name: 'app_inscription')]

    public function inscription(

        // Permet récupérer les données du formulaire
        Request $request,

        // Permet communiquer avec la base de données
        EntityManagerInterface $em,

        // Permet sécuriser le mot de passe
        UserPasswordHasherInterface $hasher

    ): Response
    {
        // Vérifie si le formulaire est envoyé

        if ($request->isMethod('POST')) {

            // Création nouvel utilisateur

            $user = new Utilisateur();

            // Récupère le nom

            $user->setNom(

                $request->request->get('nom')

            );

            // Récupère le prénom

            $user->setPrenom(

                $request->request->get('prenom')

            );

            // Récupère email

            $user->setEmail(

                $request->request->get('email')

            );

            // Crypte le mot de passe

            $hashed = $hasher->hashPassword(

                $user,

                $request->request->get('password')

            );

            // Sauvegarde mot de passe crypté

            $user->setMotDePasse($hashed);

            // Prépare ajout base de données

            $em->persist($user);

            // Envoie dans la base de données

            $em->flush();

            // Redirection accueil après inscription

            return $this->redirectToRoute(
                'app_accueil'
            );
        }

        // Affiche la page inscription

        return $this->render(
            'inscription/index.html.twig'
        );
    }
}