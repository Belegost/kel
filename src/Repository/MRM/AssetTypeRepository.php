<?php

namespace App\Repository\MRM;

use App\Entity\MRM\AssetHistory;
use App\Entity\MRM\AssetType;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\DBAL\Result;
use Doctrine\ORM\Query;
use Doctrine\ORM\Query\Expr\Join;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method AssetType|null find($id, $lockMode = null, $lockVersion = null)
 * @method AssetType|null findOneBy(array $criteria, array $orderBy = null)
 * @method AssetType[]    findAll()
 * @method AssetType[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class AssetTypeRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, AssetType::class);
    }

    public function findByCode($code)
    {
        return $this->createQueryBuilder('at')
            ->where('at.code = :code')
            ->andWhere('at.status = :status')
            ->setParameter('status', AssetType::STATUS_ACTIVE)
            ->setParameter('code', $code)
            ->setMaxResults(1)
            ->getQuery()
            ->getOneOrNullResult();
//            ->getFirstResult();
    }

    public function fetchActiveAssetsSortedByPrice(int $count = null)
    {
        return $this->getEntityManager()
            ->createQueryBuilder()
            ->select('at')
            ->from('MRM:AssetType', 'at')
            ->where('at.status = :status')
            ->andWhere('at.visible = :visible')
            ->setParameter('status', AssetType::STATUS_ACTIVE)
            ->setParameter('visible', AssetType::VISIBLE_ON)
            ->orderBy('at.price', 'DESC')
            ->setMaxResults($count)
            ->getQuery()
            ->getResult();
    }

    /**
     * @param int|null $count
     * @return int|mixed|string
     */
    public function fetchVisibleAssets(int $count = null)
    {
        return $this->createQueryBuilder('at')
            ->select('at')
            ->where('at.status = :status')
            ->setParameter('status', AssetType::STATUS_ACTIVE)
            ->andWhere('at.visible = :visible')
            ->setParameter('visible', AssetType::VISIBLE_ON)
            ->setMaxResults($count)
            ->getQuery()
            ->getResult(Query::HYDRATE_ARRAY);
    }
}
