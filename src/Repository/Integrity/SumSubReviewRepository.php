<?php

namespace App\Repository\Integrity;

use App\Entity\Integrity\SumSubReview;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method SumSubReview|null find($id, $lockMode = null, $lockVersion = null)
 * @method SumSubReview|null findOneBy(array $criteria, array $orderBy = null)
 * @method SumSubReview[]    findAll()
 * @method SumSubReview[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class SumSubReviewRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, SumSubReview::class);
    }

    // /**
    //  * @return SumSubReview[] Returns an array of SumSubReview objects
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
    public function findOneBySomeField($value): ?SumSubReview
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
