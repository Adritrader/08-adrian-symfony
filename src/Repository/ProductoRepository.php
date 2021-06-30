<?php

namespace App\Repository;

use App\Entity\Producto;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;
use Doctrine\ORM\Tools\Pagination\Paginator;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Component\HttpFoundation\Request;


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

    public function findAllPaginatedChampus($currentPage = 1):?Paginator
    {
        $query = $this->createQueryBuilder('pro')
            ->orWhere('pro.categoria LIKE :value')
            ->orderBy('pro.id', 'ASC')
            ->setParameter('value', "Champu")
            ->getQuery();


        // No need to manually get get the result ($query->getResult())
        $paginator = $this->paginate($query, $currentPage);

        return $paginator;
    }

    public function findAllPaginatedTratamientos($currentPage = 1):?Paginator
    {
        $query = $this->createQueryBuilder('pro')
            ->orWhere('pro.categoria LIKE :value')
            ->orderBy('pro.id', 'ASC')
            ->setParameter('value', "Tratamiento")
            ->getQuery();


        // No need to manually get get the result ($query->getResult())
        $paginator = $this->paginate($query, $currentPage);

        return $paginator;
    }

    public function findAllPaginatedAccesorios($currentPage = 1):?Paginator
    {
        $query = $this->createQueryBuilder('pro')
            ->orWhere('pro.categoria LIKE :value')
            ->orderBy('pro.id', 'ASC')
            ->setParameter('value', "Accesorio")
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


    public function filterPaginate(string $text, $page = 1, $limit = 4):?Paginator
    {

        $qb = $this->createQueryBuilder('pro')
            ->orWhere('pro.nombre LIKE :value')
            ->orWhere('pro.descripcion LIKE :value');

        $qb->setParameter('value', "%".$text."%");
        $qb->orderBy('pro.nombre', 'ASC');
        $qb->setMaxResults($limit);

        $paginator = new Paginator($qb);


        $paginator->getQuery()
            ->setFirstResult($limit * ($page - 1)) // Offset
            ->setMaxResults($limit);

        return $paginator;
    }




    public function getAllProductsPaginated(Request $request, PaginatorInterface $paginator)
    {

    $qb = $this->createQueryBuilder('a');
    $qb->orderBy('a.nombre', 'ASC');
    $query = $qb->getQuery();

    $pagination = $paginator->paginate($query, /* query NOT result */
    $request->query->getInt('page', 1), /*page number*/
    3 /*limit per page*/);

    return $pagination;
}


    /**
     * @param string $text
     * @param Request $request
     * @param PaginatorInterface $paginator
     * @param string $minDate
     * @param string $maxDate
     * @return \Knp\Component\Pager\Pagination\PaginationInterface
     **/
    public function getByNameDatePaginated(string $text, Request $request, PaginatorInterface $paginator, string $minDate = "00010101", string $maxDate = "30000101")
    {
    $qb = $this->createQueryBuilder('pro')
    ->where('pro.added_on BETWEEN :min AND :max')
    ->andWhere('pro.categoria LIKE :value')
    ->andWhere('pro.nombre LIKE :value');

    $qb->setParameter('min', $minDate);
    $qb->setParameter('max', $maxDate);
    $qb->setParameter('value', "%".$text."%");
    $qb->orderBy('pro.added_on', 'ASC');
    $query = $qb->getQuery();

    $pagination = $paginator->paginate(
    $query, /* query NOT result */
    $request->query->getInt('page', 1), /* page number */
    6 /* limit per page */);

    return $pagination;
}

    /**
     * @param string $text
     * @param Request $request
     * @param PaginatorInterface $paginator
     * @param string $minDate
     * @param string $maxDate
     * @return \Knp\Component\Pager\Pagination\PaginationInterface
     **/
    public function getAllProductsPaginated2(string $text, Request $request, PaginatorInterface $paginator, string $minDate = "00010101", string $maxDate = "30000101")
    {
        $qb = $this->createQueryBuilder('pro')
            ->where('pro.added_on BETWEEN :min AND :max')
            ->andWhere('pro.categoria LIKE :value')
            ->andWhere('pro.nombre LIKE :value');

        $qb->setParameter('min', $minDate);
        $qb->setParameter('max', $maxDate);
        $qb->setParameter('value', "%".$text."%");
        $qb->orderBy('pro.added_on', 'ASC');
        $query = $qb->getQuery();

        $pagination = $paginator->paginate(
            $query, /* query NOT result */
            $request->query->getInt('page', 1), /* page number */
            6 /* limit per page */);

        return $pagination;
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
