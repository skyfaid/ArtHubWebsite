<?php

namespace App\Repository;

use App\Entity\EventAccess;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<EventAccess>
 *
 * @method EventAccess|null find($id, $lockMode = null, $lockVersion = null)
 * @method EventAccess|null findOneBy(array $criteria, array $orderBy = null)
 * @method EventAccess[]    findAll()
 * @method EventAccess[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class EventAccessRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, EventAccess::class);
    }

//    /**
//     * @return EventAccess[] Returns an array of EventAccess objects
//     */
//    public function findByExampleField($value): array
//    {
//        return $this->createQueryBuilder('e')
//            ->andWhere('e.exampleField = :val')
//            ->setParameter('val', $value)
//            ->orderBy('e.id', 'ASC')
//            ->setMaxResults(10)
//            ->getQuery()
//            ->getResult()
//        ;
//    }

//    public function findOneBySomeField($value): ?EventAccess
//    {
//        return $this->createQueryBuilder('e')
//            ->andWhere('e.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }
}
