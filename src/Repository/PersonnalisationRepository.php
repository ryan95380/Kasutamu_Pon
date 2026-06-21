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
    public function __construct(ManagerRegistry $registry)
    {
        // Accès aux personnalisations
        parent::__construct($registry, Personnalisation::class);
    }
}
