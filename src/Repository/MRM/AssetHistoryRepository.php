<?php

namespace App\Repository\MRM;

use App\Entity\MRM\AssetHistory;
use App\Entity\MRM\AssetType;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\Query\Expr\Join;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method AssetHistory|null find($id, $lockMode = null, $lockVersion = null)
 * @method AssetHistory|null findOneBy(array $criteria, array $orderBy = null)
 * @method AssetHistory[]    findAll()
 * @method AssetHistory[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class AssetHistoryRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, AssetHistory::class);
    }

    public function fetchHistoricalData(string $code, int $timeframe, string $from = null, string $to = null, int $limit = null)
    {
        $query = $this->getEntityManager()
            ->createQueryBuilder()
            ->select('ah')
            ->from('MRM:AssetHistory', 'ah')
            ->join('ah.asset_type', 'at', Join::WITH)
            ->where('at.code = :code')
            ->andWhere('ah.timeframe = :timeframe')
            ->setParameter('code', $code)
            ->setParameter('timeframe', $timeframe);

        if ( $from ) {
            $query->andWhere('ah.datetime >= :from')
                ->setParameter('from', $from);
        }

        if ( $to ) {
            $query->andWhere('ah.datetime <= :to')
                ->setParameter('to', $to);
        }

            $result = $query
                ->orderBy('ah.datetime', 'DESC')
            ->setMaxResults($limit)
            ->getQuery()
            ->getResult();

        return array_reverse($result);
    }

    public function fetchDailyHistoricalData(AssetType $assetType, $limit, $timeframe)
    {

        return array_reverse($this->getEntityManager()
            ->createQueryBuilder()
            ->from('MRM:AssetHistory', 'ah')
            ->select('ah')
            ->where('ah.asset_type = :assetType')
            ->andWhere('ah.timeframe = :timeFrame')
            ->setParameter('assetType', $assetType)
            ->setParameter('timeFrame', $timeframe)
            ->orderBy('ah.datetime', 'DESC')
            ->setMaxResults($limit)
            ->getQuery()
            ->getResult());
    }

    // /**
    //  * @return AssetHistory[] Returns an array of AssetHistory objects
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
    public function findOneBySomeField($value): ?AssetHistory
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
