<?php


namespace App\Traits;


use Doctrine\Persistence\ManagerRegistry;

trait DoctrineAwareTrait
{
    private ManagerRegistry $doctrine;

    /**
     * @return ManagerRegistry
     */
    public function getDoctrine(): ManagerRegistry
    {
        return $this->doctrine;
    }

    /**
     * @param ManagerRegistry $doctrine
     */
    public function setDoctrine(ManagerRegistry $doctrine): void
    {
        $this->doctrine = $doctrine;
    }


}