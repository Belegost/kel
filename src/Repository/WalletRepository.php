<?php

namespace App\Repository;

use App\Entity\Integrity\Wallet;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\NonUniqueResultException;
use Doctrine\Persistence\ManagerRegistry;
use Doctrine\DBAL\Types\Type;

/**
 * @method Wallet|null find($id, $lockMode = null, $lockVersion = null)
 * @method Wallet|null findOneBy(array $criteria, array $orderBy = null)
 * @method Wallet[]    findAll()
 * @method Wallet[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class WalletRepository extends ServiceEntityRepository {
    public function __construct(ManagerRegistry $registry) {
        parent::__construct($registry, Wallet::class);
    }

    public function findDepositWallet(string $accountId, string $walletId): ?Wallet {
        try {
            return $this->createQueryBuilder('w')
                ->andWhere('w.address = :address')
                ->andWhere('w.account_id = :accountId')
                ->andWhere('w.type = :type')
                ->setParameter('address', $walletId)
                ->setParameter('accountId', $accountId)
                ->setParameter('type', Wallet::TYPE_CREDIT_WALLET)
                ->getQuery()
                ->getOneOrNullResult();
        } catch (NonUniqueResultException $e) {
        }
        return null;
    }

    public function findDebitWallets(string $accountId): array {
        return $this->findBy(['account_id' => $accountId, 'type' => Wallet::TYPE_DEBIT_WALLET]);
    }

    /**
     * @param int $id
     * @param array $operations
     * @return int|mixed
     */
    public function updateOperations(int $id, array $operations) {
        try {
            return $this->createQueryBuilder('w')
                ->update()
                ->set("w.operations", ":operations")
                ->setParameter('operations', serialize($operations), Type::STRING)
                ->andWhere("w.id = :id")
                ->setParameter('id', $id, Type::INTEGER)
                ->getQuery()
                ->getSingleScalarResult();
        } catch (NonUniqueResultException $e) {
        }
        return 0;
    }


}
