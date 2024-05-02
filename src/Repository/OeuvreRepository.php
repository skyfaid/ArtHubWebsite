<?php
namespace App\Repository;

use App\Entity\Oeuvre;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method Oeuvre|null find($id, $lockMode = null, $lockVersion = null)
 * @method Oeuvre|null findOneBy(array $criteria, array $orderBy = null)
 * @method Oeuvre[]    findAll()
 * @method Oeuvre[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class OeuvreRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Oeuvre::class);
    }


   /**
     * Recherche des œuvres basée sur le titre.
     * 
     * @param string $searchTerm Le terme de recherche.
     * @return array Une liste d'œuvres qui correspondent au terme de recherche.
     */
 
    
    public function findByTitleLike($search)
{
    return $this->createQueryBuilder('o')
        ->andWhere('o.titre LIKE :search')
        ->setParameter('search', '%' . $search . '%')
        ->getQuery()
        ->getResult();
}


}
