<?php

namespace App\Controller;

use App\Entity\Utilisateur;
use App\Repository\UtilisateurRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Routing\Attribute\Route;

class InscriptionController extends AbstractController
{
    #[Route('/inscription', name: 'app_inscription')]
    public function inscription(
        Request $request,
        EntityManagerInterface $em,
        UtilisateurRepository $utilisateurRepository,
        UserPasswordHasherInterface $hasher
    ): Response {
        $erreur = null;
        $nom = '';
        $prenom = '';
        $email = '';

        if ($request->isMethod('POST')) {
            $nom = trim($request->request->get('nom', ''));
            $prenom = trim($request->request->get('prenom', ''));
            $email = trim($request->request->get('email', ''));
            $password = $request->request->get('password', '');

            if ($utilisateurRepository->findOneBy(['email' => $email])) {
                $erreur = 'Cet email est deja utilise.';
            } else {
                $user = new Utilisateur();
                $user->setNom($nom);
                $user->setPrenom($prenom);
                $user->setEmail($email);
                $user->setMotDePasse($hasher->hashPassword($user, $password));
                $user->setRoles(['ROLE_USER']);

                $em->persist($user);
                $em->flush();

                return $this->redirectToRoute('app_accueil');
            }
        }

        return $this->render('inscription/index.html.twig', [
            'erreur' => $erreur,
            'nom' => $nom,
            'prenom' => $prenom,
            'email' => $email,
        ]);
    }
}
