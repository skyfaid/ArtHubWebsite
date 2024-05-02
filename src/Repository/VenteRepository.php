<?php
namespace App\Repository;

use App\Entity\Vente;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;
use Doctrine\ORM\QueryBuilder;

/**
 * @method Vente|null find($id, $lockMode = null, $lockVersion = null)
 * @method Vente|null findOneBy(array $criteria, array $orderBy = null)
 * @method Vente[]    findAll()
 * @method Vente[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class VenteRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Vente::class);
    }

    public function findBySearch($term)
    {
        $queryBuilder = $this->createQueryBuilder('v');
    
    if (is_numeric($term)) {
        $queryBuilder->andWhere('v.id = :term')
                     ->setParameter('term', (int) $term);
    }
    // Vous pouvez ajouter ici des conditions supplémentaires pour d'autres champs si nécessaire.

    return $queryBuilder->getQuery()->getResult();
    }
    public function getMonthlySalesData()
{
    $qb = $this->createQueryBuilder('v')
        ->select('SUBSTRING(v.datevente, 1, 7) as month, SUM(v.prixvente) as totalSales')
        ->groupBy('month')
        ->orderBy('month', 'ASC');

    return $qb->getQuery()->getResult();
}
// Exemple de méthode dans VenteRepository utilisant QueryBuilder
public function findByDate(\DateTime $date) {
    return $this->createQueryBuilder('v')
        ->andWhere('v.datevente = :date')
        ->setParameter('date', $date)
        ->getQuery()
        ->getResult();
}
// Dans votre VenteRepository
public function findTopVentesParOeuvre(int $limit = 10)
{  
    $qb = $this->createQueryBuilder('v')
    ->select('o.titre AS oeuvreTitre, COUNT(v) AS nombreVentes')
    ->leftJoin('v.oeuvre', 'o')
    ->groupBy('o.id')
    ->orderBy('nombreVentes', 'DESC')
    ->setMaxResults($limit);

return $qb->getQuery()->getResult();

}

}
