<?php

namespace App\Repository;

use App\Entity\Integrity\Account;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

class AccountRepository extends ServiceEntityRepository {
    public function __construct(ManagerRegistry $registry) {
        parent::__construct($registry, Account::class);
    }

    /**
     * @param $target
     * @return null|Account
     */
    public function findByUsernameOrPublicKey($target) {
        foreach (['username', 'public_key'] as $field) {
            /** @var Account $account */
            if (($account = $this->findOneBy([$field => $target])) instanceof Account) {
                return $account;
            }
        }

        return null;
    }

    /**
     * @param $value
     * @return Account|null
     * @throws \Doctrine\ORM\NonUniqueResultException
     */
    public function findOneByUniqueField($value): ?Account {
        return $this->createQueryBuilder('a')
            ->orWhere('a.username = :value')
            ->orWhere('a.public_key = :value')
            ->orWhere('a.email = :value')
            ->orWhere('a.phone_number = :value')
            ->setParameter('value', $value)
            ->getQuery()
            ->getOneOrNullResult();
    }
}
