<?php

namespace App\Repository\Analytics;

use App\Entity\Integrity\Analytics\Asset;
use App\Entity\Integrity\Analytics\Capitalization;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * Class CapitalizationRepository
 * @package App\Repository\Analytics
 */
class CapitalizationRepository extends ServiceEntityRepository
{
    /**
     * AccountRepository constructor
     *
     * @param ManagerRegistry $registry
     */
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Capitalization::class);
    }

    /**
     * @param Asset $asset
     * @param int   $limit
     *
     * @return Capitalization[]
     */
    public function findByAsset(Asset $asset, $limit = 500)
    {
        return $this->createQueryBuilder('c')
            ->where('c.asset = :asset')
            ->setParameters([
                'asset' => $asset
            ])
            ->setMaxResults($limit)
            ->orderBy('c.createdDate', 'DESC')
            ->getQuery()
            ->getResult();
    }
}
