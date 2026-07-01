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
    // Ce repository sert à retrouver les options de personnalisation en base.
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Personnalisation::class);
    }
}
