<?php

// src/Repository/ActiviteRepository.php

namespace App\Repository;

use App\Entity\Activite;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;


class ActiviteRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Activite::class);
    }

    // Custom method to search activities
    public function findBySearchQuery(string $query): array
{
    return $this->createQueryBuilder('a')
        ->andWhere('a.nomAct LIKE :query')
        ->setParameter('query', '%' . $query . '%')
        ->getQuery()
        ->getResult();
}

public function findByIdAsc()
    {
        return $this->findBy([], ['idActivite' => 'ASC']);
    }

    public function findByIdDesc()
    {
        return $this->findBy([], ['idActivite' => 'DESC']);
    }
}