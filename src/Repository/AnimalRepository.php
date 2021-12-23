<?php

namespace App\Repository;

use App\Entity\Animal;
use App\Entity\SearchAnimal;
use Doctrine\Persistence\ManagerRegistry;
use Knp\Component\Pager\PaginatorInterface;
use Knp\Component\Pager\Pagination\PaginationInterface;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;

/**
 * @method Animal|null find($id, $lockMode = null, $lockVersion = null)
 * @method Animal|null findOneBy(array $criteria, array $orderBy = null)
 * @method Animal[]    findAll()
 * @method Animal[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class AnimalRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry, PaginatorInterface $paginator)
    {
        parent::__construct($registry, Animal::class);
        $this->paginator = $paginator;
    }


    /*
        La requête retourne tous les animaux où dateArrived est entre dateStart et dateNow
        La valeur de dateStart est de 30j avant dateNow
        Les dates prennent format année-mois-jour puis heure:minute:second
    */
    public function findByDateArrivedThirtyDays($dateStart, $dateNow) {
        return $this->createQueryBuilder('a')
            ->where('a.dateArrived BETWEEN :start AND :end')
            ->setParameter('start', $dateStart->format('Y-m-d H:m:s'))
            ->setParameter('end', $dateNow->format('Y-m-d H:m:s'))
            ->getQuery()
            ->getResult();
    }

    /**
     * @return PaginationInterface
     */
    public function animalSearch(SearchAnimal $search): PaginationInterface
    {
        $query = $this
            ->createQueryBuilder('a')
            ->select('type', 'a')
            ->join('a.type', 'type');

        if (!empty($search->word)) {
            $query = $query
                ->andWhere('a.name LIKE :word')
                ->setParameter('word', "%{$search->word}%");
        }

        if (!empty($search->age)) {
            $query = $query
                ->andWhere('a.age < :age')
                ->setParameter('age', $search->age);
        }

        if (!empty($search->sterilised)) {
            $query = $query
                ->andWhere('a.sterilised = 1');
        }

        if (!empty($search->categories)) {
            $query = $query
                ->andWhere('type.id IN (:categories)')
                ->setParameter('categories', $search->categories);
        }

        $query = $query->getQuery();
        return $this->paginator->paginate(
            $query,
            $search->page,
            10
        );
    }
    // /**
    //  * @return Animal[] Returns an array of Animal objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('a')
            ->andWhere('a.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('a.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?Animal
    {
        return $this->createQueryBuilder('a')
            ->andWhere('a.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
