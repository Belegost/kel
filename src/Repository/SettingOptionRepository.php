<?php

namespace App\Repository;

use App\Entity\Integrity\SettingOption;
use Doctrine\Persistence\ManagerRegistry;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;

/**
 * Class SettingOptionRepository
 */
class SettingOptionRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry) {
        parent::__construct($registry, SettingOption::class);
    }
}
