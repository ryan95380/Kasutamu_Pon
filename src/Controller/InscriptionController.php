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
    // Inscription
    #[Route('/inscription', name: 'app_inscription')]

    public function inscription(

        Request $request,
        EntityManagerInterface $em,
        UserPasswordHasherInterface $hasher

    ): Response
    {
        // Traitement formulaire
        if ($request->isMethod('POST')) {
            // Création utilisateur
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

            // Hachage mot de passe
            $hashed = $hasher->hashPassword(

                $user,

                $request->request->get('password')

            );

            $user->setMotDePasse($hashed);
            // Persistance BDD
            $em->persist($user);
            $em->flush();

            return $this->redirectToRoute(
                'app_accueil'
            );
        }

        return $this->render(
            'inscription/index.html.twig'
        );
    }
}
