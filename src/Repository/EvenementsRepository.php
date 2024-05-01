<?php

namespace App\Repository;

use App\Entity\Evenements;
use App\Entity\EventAccess;
use Doctrine\ORM\ORMException;
use Doctrine\Persistence\ManagerRegistry;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;

/**
 * @extends ServiceEntityRepository<Evenements>
 *
 * @method Evenements|null find($id, $lockMode = null, $lockVersion = null)
 * @method Evenements|null findOneBy(array $criteria, array $orderBy = null)
 * @method Evenements[]    findAll()
 * @method Evenements[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class EvenementsRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Evenements::class);
    }

    // Example of a custom repository method
    public function findByEventName(string $name): ?Evenements
    {
        return $this->createQueryBuilder('e')
            ->andWhere('e.nom = :val')
            ->setParameter('val', $name)
            ->getQuery()
            ->getOneOrNullResult();
    }
   /* public function participateInEvent(int $eventId, int $userId = 100): bool {
        $event = $this->find($eventId);
        if ($event && $event->getNombreplaces() > 0) {
            $event->setNombreparticipants($event->getNombreparticipants() + 1);
            $event->setNombreplaces($event->getNombreplaces() - 1);
            $this->getEntityManager()->flush();
            return true;
        }
        return false;
    }*/
    public function participateInEvent(int $eventId, int $userId): bool {
        $event = $this->find($eventId);
        if ($event && $event->getNombreplaces() > 0) {
            $event->setNombreparticipants($event->getNombreparticipants() + 1);
            $event->setNombreplaces($event->getNombreplaces() - 1);
            $this->getEntityManager()->flush();
            return true;
        }
        return false;
    }
    
    public function userHasAccessToEvent(int $userId, int $eventId): bool
    {
        $result = $this->getEntityManager()
            ->getRepository(EventAccess::class)
            ->createQueryBuilder('ea')
            ->select('count(ea.id)')
            ->where('ea.user = :userId AND ea.event = :eventId')
            ->setParameters([
                'userId' => $userId,
                'eventId' => $eventId
            ])
            ->getQuery()
            ->getSingleScalarResult();

        return $result > 0;
    }


    
    public function quitEvent(int $eventId, int $userId): bool {
        $event = $this->find($eventId);
        if ($event && $event->getNombreparticipants() > 0) {
            $event->setNombreparticipants($event->getNombreparticipants() - 1);
            $event->setNombreplaces($event->getNombreplaces() + 1);
            $this->getEntityManager()->flush();
            return true;
        }
        return false;
    }
    public function findNearestNextEvent(): ?Evenements
{
    return $this->createQueryBuilder('e')
        ->andWhere('e.datedebut > :currentDate')
        ->setParameter('currentDate', new \DateTime())
        ->orderBy('e.datedebut', 'ASC')
        ->setMaxResults(1)
        ->getQuery()
        ->getOneOrNullResult();
}

public function findExclusiveEvents(): array
{
    return $this->createQueryBuilder('e')
        ->andWhere('e.isExclusive = :isExclusive')
        ->setParameter('isExclusive', true)
        ->setMaxResults(4)
        ->getQuery()
        ->getResult();
}



public function findEventsByUser(int $userId)
{
    return $this->createQueryBuilder('e')
        ->innerJoin('App\Entity\Participants', 'p', 'WITH', 'p.event = e.id')
        ->where('p.utilisateurId = :userId')
        ->setParameter('userId', $userId)
        ->getQuery()
        ->getResult();
}


}

