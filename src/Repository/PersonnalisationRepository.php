<?php

namespace App\Repository;

use App\Entity\Personnalisation;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Personnalisation>
 */
class PersonnalisationRepository extends ServiceEntityRepository
{
    // Connecte ce repository à l'entité Personnalisation.
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Personnalisation::class);
    }
}
