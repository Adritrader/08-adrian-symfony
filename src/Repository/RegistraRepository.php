<?php

namespace App\Repository;

use App\Entity\Registra;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method Registra|null find($id, $lockMode = null, $lockVersion = null)
 * @method Registra|null findOneBy(array $criteria, array $orderBy = null)
 * @method Registra[]    findAll()
 * @method Registra[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class RegistraRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Registra::class);
    }

    /**
     * @return Registra[] Returns an array of Producto objects
     */
    public function lastReserves(): array
    {
        $qb = $this->createQueryBuilder('res');
        $qb->orderBy('res.id', 'DESC');
        $qb->setMaxResults(4);
        //$qb->setFirstResult(4);
        $query = $qb->getQuery();
        return $query->getResult();
    }

    /**
     * @return Registra[] Returns an array of Producto objects
     */

    public function activeReserves(): array
    {
        $qb = $this->createQueryBuilder('res')
        ->where('res.active = true');
        $qb->orderBy('res.fecha', 'DESC');
        $qb->setMaxResults(10);
        //$qb->setFirstResult(4);
        $query = $qb->getQuery();
        return $query->getResult();
    }
    // /**
    //  * @return Registra[] Returns an array of Registra objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('r')
            ->andWhere('r.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('r.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?Registra
    {
        return $this->createQueryBuilder('r')
            ->andWhere('r.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
