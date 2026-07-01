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
    // Affiche et traite le formulaire d'inscription.
    #[Route('/inscription', name: 'app_inscription')]

    public function inscription(

        Request $request,
        EntityManagerInterface $em,
        UserPasswordHasherInterface $hasher

    ): Response
    {
        // Vérifie les informations envoyées avant de créer le compte.
        if ($request->isMethod('POST')) {
            // Prépare le nouvel utilisateur avec les champs du formulaire.
            $user = new Utilisateur();

            $user->setNom(

                $request->request->get('nom')

            );

            $user->setPrenom(

                $request->request->get('prenom')

            );

            $user->setEmail(

                $request->request->get('email')

            );

            // Hash le mot de passe avant l'enregistrement en base.
            $hashed = $hasher->hashPassword(

                $user,

                $request->request->get('password')

            );

            $user->setMotDePasse($hashed);
            // Enregistre le nouvel utilisateur avec Doctrine.
            $em->persist($user);
            $em->flush();

            return $this->redirectToRoute(
                'app_accueil'
            );
        }

        // Affiche le formulaire d'inscription.
        return $this->render(
            'inscription/index.html.twig'
        );
    }
}
