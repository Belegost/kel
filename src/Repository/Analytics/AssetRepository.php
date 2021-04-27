<?php

namespace App\Repository\Analytics;

use App\Entity\Integrity\Analytics\Asset;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * Class AssetRepository
 * @package App\Repository\Analytics
 */
class AssetRepository extends ServiceEntityRepository
{
    /**
     * AccountRepository constructor
     *
     * @param RegistryInterface $registry
     */
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Asset::class);
    }
}
