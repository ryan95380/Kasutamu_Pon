<?php

namespace App\Repository;

use App\Entity\Figurine;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Figurine>
 */
class FigurineRepository extends ServiceEntityRepository
{
    // Ce repository permet de récupérer les figurines affichées dans le catalogue.
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Figurine::class);
    }
}
