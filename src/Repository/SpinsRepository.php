<?php

namespace App\Repository;

use App\Entity\Spins;
use App\Entity\Utilisateurs;
use Doctrine\Persistence\ManagerRegistry;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;

/**
 * @method Spins|null find($id, $lockMode = null, $lockVersion = null)
 * @method Spins|null findOneBy(array $criteria, array $orderBy = null)
 * @method Spins[]    findAll()
 * @method Spins[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class SpinsRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Spins::class);
    }
    public function canUserSpin(int $userId): bool
    {
        $eightHoursAgo = new \DateTime('8 hours ago');

        $query = $this->createQueryBuilder('s')
                      ->andWhere('s.utilisateur = :userId')
                      ->andWhere('s.lastSpin > :eightHoursAgo')
                      ->setParameter('userId', $userId)
                      ->setParameter('eightHoursAgo', $eightHoursAgo)
                      ->getQuery();

        return count($query->getResult()) === 0;
    }

    public function recordSpin(int $userId): void
    {
        $entityManager = $this->getEntityManager();
        $user = $entityManager->getReference(Utilisateurs::class, $userId);

        $spin = new Spins();
        $spin->setUtilisateur($user);
        $spin->setLastSpin(new \DateTime());

        $entityManager->persist($spin);
        $entityManager->flush();
    }
    
    public function getTimeUntilNextSpin(int $userId): int
    {
        $lastSpin = $this->findOneBy(['utilisateur' => $userId], ['lastSpin' => 'DESC']);
        if (!$lastSpin) {
            return 0;
        }
    
        $nextAvailableSpin = (clone $lastSpin->getLastSpin())->modify('+8 hours');
        $now = new \DateTime();
        if ($nextAvailableSpin > $now) {
            return $nextAvailableSpin->getTimestamp() - $now->getTimestamp();
        }
        return 0;
    }
    
    public function findLastSpinByUserId(int $userId): ?Spins
    {
        return $this->findOneBy(['utilisateur' => $userId], ['lastSpin' => 'DESC']);
    }


    
    
}
