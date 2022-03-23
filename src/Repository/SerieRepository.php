<?php

namespace App\Repository;

use App\Entity\Serie;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\OptimisticLockException;
use Doctrine\ORM\ORMException;
use Doctrine\ORM\Tools\Pagination\Paginator;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method Serie|null find($id, $lockMode = null, $lockVersion = null)
 * @method Serie|null findOneBy(array $criteria, array $orderBy = null)
 * @method Serie[]    findAll()
 * @method Serie[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class SerieRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Serie::class);
    }

    public function findBestSeries()
    {
        //en DQL
//        $entityManager = $this->getEntityManager();
//        $dql = "
//            SELECT s
//            FROM App\Entity\Serie s
//            WHERE s.popularity>100
//            AND s.vote>8
//            ORDER BY s.popularity DESC
//            ";
//
//        $query = $entityManager->createQuery($dql);
//        $query->setMaxResults(50);
//        $results = $query->getResult();
//
//        dump($results);
//
//        return $results;

        //version QueryBuilder
        $queryBuilder = $this->createQueryBuilder('s');

        $queryBuilder->leftJoin('s.seasons', 'seasons')->addSelect('seasons');

        $queryBuilder->andWhere('s.popularity>200');
        $queryBuilder->andWhere('s.vote>7');
        $queryBuilder->addOrderBy('s.popularity', 'DESC');
        $query = $queryBuilder->getQuery();

        $query->setMaxResults(50);

        $paginator = new Paginator($query);

//        $results = $query->getResult();
//
//        dump($results);

        return $paginator;

    }

    /**
     * @throws ORMException
     * @throws OptimisticLockException
     */
    public function add(Serie $entity, bool $flush = true): void
    {
        $this->_em->persist($entity);
        if ($flush) {
            $this->_em->flush();
        }
    }

    /**
     * @throws ORMException
     * @throws OptimisticLockException
     */
    public function remove(Serie $entity, bool $flush = true): void
    {
        $this->_em->remove($entity);
        if ($flush) {
            $this->_em->flush();
        }
    }

    // /**
    //  * @return Serie[] Returns an array of Serie objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('s')
            ->andWhere('s.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('s.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?Serie
    {
        return $this->createQueryBuilder('s')
            ->andWhere('s.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
