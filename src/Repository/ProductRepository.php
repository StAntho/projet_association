<?php

namespace App\Repository;

use Doctrine\ORM\Query;
use App\Entity\Product;
use App\Entity\SearchProduct;
use Doctrine\Persistence\ManagerRegistry;
use Doctrine\DBAL\Query\QueryBuilder;
use Doctrine\ORM\QueryBuilder as ORMQueryBuilder;
use Knp\Component\Pager\PaginatorInterface;
use Knp\Component\Pager\Pagination\PaginationInterface;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;

/**
 * @method Product|null find($id, $lockMode = null, $lockVersion = null)
 * @method Product|null findOneBy(array $criteria, array $orderBy = null)
 * @method Product[]    findAll()
 * @method Product[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class ProductRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry, PaginatorInterface $paginator)
    {
        parent::__construct($registry, Product::class);
        $this->paginator = $paginator;
    }

    /**
     * @return PaginationInterface
     */
    public function productSearch(SearchProduct $search): PaginationInterface
    {
        $query = $this
            ->createQueryBuilder('p')
            ->select('cat', 'p')
            ->join('p.category', 'cat');

        if (!empty($search->word)) {
            $query = $query
                ->andWhere('p.name LIKE :word')
                ->setParameter('word', "%{$search->word}%");
        }

        if (!empty($search->price)) {
            $query = $query
                ->andWhere('p.price < :price')
                ->setParameter('price', $search->price);
        }

        // if (!empty($search->inStock)) {
        //     $query = $query
        //         ->andWhere('p.inStock = 1');
        // }

        if (!empty($search->categories)) {
            $query = $query
                ->andWhere('cat.id IN (:categories)')
                ->setParameter('categories', $search->categories);
        }

        $query = $query->getQuery();
        return $this->paginator->paginate(
            $query,
            $search->page,
            10
        );
    }

    /*
    public function findOneBySomeField($value): ?Product
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
