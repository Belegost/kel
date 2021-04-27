<?php

namespace App\Repository\MRM;

use App\Entity\MRM\Document;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

class DocumentRepository extends ServiceEntityRepository {

    /**
     * DocumentRepository constructor
     *
     * @param ManagerRegistry $registry
     */
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Document::class);
    }

    /**
     * @param int $accountId
     *
     * @return Document[]|null
     */
    public function findByAccountId(int $accountId)
    {
        return $this->createQueryBuilder('d')
            ->where('d.accountId = :account_id')
            ->join('d.type', 't')
            ->join('d.state', 's')
            ->groupBy('d.type')
            ->setParameter('account_id', $accountId)
            ->getQuery()
            ->getResult();
    }
}
