<?php
/**
 * Created by PhpStorm.
 * User: Grand
 * Date: 10.05.2018
 * Time: 21:15
 */

namespace App\Lib;

use App\Entity\Integrity\Wallet;
use App\Repository\WalletRepository;
use Doctrine\Persistence\ManagerRegistry;
use Doctrine\Persistence\ObjectRepository;
use Doctrine\Persistence\ObjectManager;

class DBClient {
    const MANAGER_MRM = 'mrm';
    const MANAGER_INTEGRITY = 'integrity';

    private $doctrine;

    public function __construct(ManagerRegistry $doctrine) {
        $this->doctrine = $doctrine;
    }

    protected function getDoctrine(): ManagerRegistry {
        return $this->doctrine;
    }

    public function getRepository($persistentObject, $manager = null): ObjectRepository {
        return $this->getDoctrine()->getRepository($persistentObject, $manager);
    }

    public function getEntityManager($name = null): ObjectManager {
        return $this->getDoctrine()->getManager($name);
    }

    public function flushEntityObject(&$object, $manager = null) {
        $em = $this->getEntityManager($manager);
        $em->persist($object);
        $em->flush();
    }

    /**
     * @return WalletRepository
     */
    public function getWalletRepository(): WalletRepository {
        return $this->getRepository(Wallet::class);
    }
}