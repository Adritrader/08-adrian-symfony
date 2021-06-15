<?php

namespace App\Repository;

use App\Entity\Producto;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;
use Doctrine\ORM\Tools\Pagination\Paginator;

/**
 * @method Producto|null find($id, $lockMode = null, $lockVersion = null)
 * @method Producto|null findOneBy(array $criteria, array $orderBy = null)
 * @method Producto[]    findAll()
 * @method Producto[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class ProductoRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Producto::class);
    }


    /**
     * @return Producto[] Returns an array of Producto objects
     */

    public function filterByText(string $text): array
    {
        $qb = $this->createQueryBuilder('pro')
            ->orWhere('pro.nombre LIKE :value')
            ->orWhere('pro.descripcion LIKE :value');

        $qb->setParameter('value', "%".$text."%");
        $qb->orderBy('pro.nombre', 'ASC');
        $qb->setMaxResults(4);
        //$qb->setFirstResult(4);
        $query = $qb->getQuery();
        return $query->getResult();
    }

    /**
     * @return Producto[] Returns an array of Producto objects
     */

    public function lastProducts(): array
    {
        $qb = $this->createQueryBuilder('pro');
        $qb->orderBy('pro.id', 'DESC');
        $qb->setMaxResults(4);
        //$qb->setFirstResult(4);
        $query = $qb->getQuery();
        return $query->getResult();
    }

    public function findAllPaginated($currentPage = 1):?Paginator
    {
        $query = $this->createQueryBuilder('pro')
            ->orderBy('pro.id', 'ASC')
            ->getQuery();

        // No need to manually get get the result ($query->getResult())
        $paginator = $this->paginate($query, $currentPage);

        return $paginator;
    }

    // Paginate results.

    public function paginate($dql, $page = 1, $limit = 4):?Paginator
    {
        $paginator = new Paginator($dql);

        $paginator->getQuery()
            ->setFirstResult($limit * ($page - 1)) // Offset
            ->setMaxResults($limit); // Limit

        return $paginator;
    }

    // /**
    //  * @return Producto[] Returns an array of Producto objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('p')
            ->andWhere('p.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('p.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?Producto
    {
        return $this->createQueryBuilder('p')
            ->andWhere('p.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
