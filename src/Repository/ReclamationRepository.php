<?php
namespace App\Repository;

use App\Entity\Reclamation;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method Reclamation|null find($id, $lockMode = null, $lockVersion = null)
 * @method Reclamation|null findOneBy(array $criteria, array $orderBy = null)
 * @method Reclamation[]    findAll()
 * @method Reclamation[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class ReclamationRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Reclamation::class);
    }

    public function countReclamationsByOeuvre(): array
    {
        return $this->createQueryBuilder('r')
            ->select('o.titre AS title, COUNT(r.ReclamationID) AS count')
            ->join('r.oeuvre', 'o')
            ->groupBy('o.titre')
            ->orderBy('count', 'DESC')
            ->setMaxResults(4)
            ->getQuery()
            ->getResult();
    }
}

