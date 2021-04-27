<?php

namespace App\Repository\MRM;

use App\Entity\MRM\ClientProducts;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method ClientProducts|null find($id, $lockMode = null, $lockVersion = null)
 * @method ClientProducts|null findOneBy(array $criteria, array $orderBy = null)
 * @method ClientProducts[]    findAll()
 * @method ClientProducts[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class ClientProductsRepository extends ServiceEntityRepository {
    public function __construct(ManagerRegistry $registry) {
        parent::__construct($registry, ClientProducts::class);
    }

//    /**
//     * @return ClientProduct[] Returns an array of ClientProduct objects
//     */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('c')
            ->andWhere('c.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('c.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    public function findAllActive(int $clientId): ?array {
        return $this->createQueryBuilder('p')
            ->andWhere('p.client_id = :client_id')
            ->andWhere('p.start_date is not NULL')
            ->andWhere('p.end_date is NULL')
            ->setParameter('client_id', $clientId)
            ->getQuery()
            ->getResult();
    }

}
