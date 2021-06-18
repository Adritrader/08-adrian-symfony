<?php

namespace App\Repository;

use App\Entity\Usuario;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\Tools\Pagination\Paginator;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method Usuario|null find($id, $lockMode = null, $lockVersion = null)
 * @method Usuario|null findOneBy(array $criteria, array $orderBy = null)
 * @method Usuario[]    findAll()
 * @method Usuario[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class UsuarioRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Usuario::class);
    }


    /**
     * @return Usuario[] Returns an array of Producto objects
     */

    public function filterByText(string $text): array
    {
        $qb = $this->createQueryBuilder('usu')
            ->orWhere('usu.username LIKE :value')
            ->orWhere('usu.role LIKE :value');

        $qb->setParameter('value', "%".$text."%");
        $qb->orderBy('usu.nombre', 'ASC');
        $qb->setMaxResults(4);
        //$qb->setFirstResult(4);
        $query = $qb->getQuery();
        return $query->getResult();
    }

    /**
     * @return Usuario[] Returns an array of Producto objects
     */

    public function lastUsers(): array
    {
        $qb = $this->createQueryBuilder('usu');
        $qb->orderBy('usu.id', 'DESC');
        $qb->setMaxResults(4);
        //$qb->setFirstResult(4);
        $query = $qb->getQuery();
        return $query->getResult();
    }

    public function findAllPaginated($currentPage = 1):?Paginator
    {
        $query = $this->createQueryBuilder('usu')
            ->orderBy('usu.id', 'ASC')
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
    //  * @return Usuario[] Returns an array of Usuario objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('u')
            ->andWhere('u.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('u.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?Usuario
    {
        return $this->createQueryBuilder('u')
            ->andWhere('u.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
