<?php

namespace App\Repository;

use App\Entity\Evenements;
use App\Entity\Participants; 
use Doctrine\Persistence\ManagerRegistry;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;

/**
 * @method Participants|null find($id, $lockMode = null, $lockVersion = null)
 * @method Participants|null findOneBy(array $criteria, array $orderBy = null)
 * @method Participants[]    findAll()
 * @method Participants[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class ParticipantsRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Participants::class);
    }

// Add custom repository methods here
public function isUserParticipating(int $eventId, int $userId = 100): bool {
    $participant = $this->findOneBy([
        'utilisateurId' => $userId,
        'event' => $eventId
    ]);

    return $participant !== null;
}

/*
public function addParticipant(int $eventId, int $userId = 100): bool {
    $entityManager = $this->getEntityManager();
    $event = $entityManager->getRepository(Evenements::class)->find($eventId);
    $participant = new Participants();
    $participant->setUtilisateurId($userId);
    $participant->setEvent($event);

    $entityManager->persist($participant);
    $entityManager->flush();

    return true;
}

public function removeParticipant(int $eventId, int $userId = 100): bool {
    $entityManager = $this->getEntityManager();
    $participant = $this->findOneBy(['utilisateurId' => $userId, 'event' => $eventId]);

    if ($participant) {
        $entityManager->remove($participant);
        $entityManager->flush();
        return true;
    }

    return false;
}
*/
public function addParticipant(int $eventId, int $userId): bool {
    $entityManager = $this->getEntityManager();
    $event = $entityManager->getRepository(Evenements::class)->find($eventId);
    $participant = new Participants();
    $participant->setUtilisateurId($userId);
    $participant->setEvent($event);

    $entityManager->persist($participant);
    $entityManager->flush();

    return true;
}

public function removeParticipant(int $eventId, int $userId): bool {
    $entityManager = $this->getEntityManager();
    $participant = $this->findOneBy(['utilisateurId' => $userId, 'event' => $eventId]);

    if ($participant) {
        $entityManager->remove($participant);
        $entityManager->flush();
        return true;
    }

    return false;
}



public function findParticipantsByEvent(int $eventId): array
{
    $entityManager = $this->getEntityManager();
    $query = $entityManager->createQuery(
        'SELECT p.participantId, p.utilisateurId, u.nom, u.prenom, u.gender
        FROM App\Entity\Participants p
        JOIN App\Entity\Utilisateurs u WITH p.utilisateurId = u.utilisateurId
        WHERE p.event = :eventId'
    )->setParameter('eventId', $eventId);

    return $query->getResult();
}



public function deleteParticipantById(int $participantId): bool
{
    $entityManager = $this->getEntityManager();
    $participant = $this->find($participantId);

    if ($participant) {
        $entityManager->remove($participant);
        $entityManager->flush();
        return true;
    }

    return false;
}




}
