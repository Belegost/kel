<?php

namespace App\Repository\Analytics;

use App\Entity\Integrity\Analytics\Account;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * Class AccountRepository
 * @package App\Repository\Analytics
 */
class AccountRepository extends ServiceEntityRepository
{
    /**
     * AccountRepository constructor
     *
     * @param ManagerRegistry $registry
     */
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Account::class);
    }
}
