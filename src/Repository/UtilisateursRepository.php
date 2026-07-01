<?php

namespace App\Repository;

use App\Entity\Utilisateurs;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Utilisateurs>
 */
class UtilisateursRepository extends ServiceEntityRepository
{
    // Ancien repository conservé, mais le projet utilise maintenant UtilisateurRepository.
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Utilisateurs::class);
    }
}
