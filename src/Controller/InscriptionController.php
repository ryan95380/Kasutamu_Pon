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
    // Cette route affiche la page d'inscription et traite le formulaire quand il est validé.
    #[Route('/inscription', name: 'app_inscription')]

    public function inscription(

        Request $request,
        EntityManagerInterface $em,
        UserPasswordHasherInterface $hasher

    ): Response
    {
        // Si le formulaire est envoyé en POST, on récupère les informations saisies par l'utilisateur.
        if ($request->isMethod('POST')) {
            // Création d'un nouvel objet Utilisateur qui sera enregistré en base de données.
            $user = new Utilisateur();

            $user->setNom(

                $request->request->get('nom')

            );

            // Récupération des autres données du formulaire : prénom et email.
            $user->setPrenom(

                $request->request->get('prenom')

            );

            $user->setEmail(

                $request->request->get('email')

            );

            // Le mot de passe est haché avec Symfony pour ne jamais être stocké en clair.
            $hashed = $hasher->hashPassword(

                $user,

                $request->request->get('password')

            );

            $user->setMotDePasse($hashed);

            // On enregistre le role de base dans la colonne roles.
            $user->setRoles(['ROLE_USER']);

            // Doctrine prépare l'enregistrement de l'utilisateur.
            $em->persist($user);

            // Exécution de la requête SQL pour enregistrer définitivement l'utilisateur.
            $em->flush();

            // Une fois l'inscription terminée, l'utilisateur est redirigé vers l'accueil.
            return $this->redirectToRoute(
                'app_accueil'
            );
        }

        // Au premier chargement de la page, on affiche simplement le formulaire.
        return $this->render(
            'inscription/index.html.twig'
        );
    }
}
