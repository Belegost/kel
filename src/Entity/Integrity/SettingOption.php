<?php

namespace App\Entity\Integrity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;

/**
 * @ORM\Entity(repositoryClass="App\Repository\SettingOptionRepository")
 * @ORM\Table(name="setting_options")
 * @UniqueEntity(fields={"`name`"}, message="The same name of the option olready exist")
 */
class SettingOption
{
    /**
     * @ORM\Id
     * @ORM\Column(type="smallint")
     */
    private int $id;

    /**
     * @ORM\Column(type="smallint", name="`type_id`")
     */
    private int $type_id;

    /**
     * @ORM\Column(type="string", name="`name`", length=128, unique=true)
     */
    private string $name;

    /**
     * @return int
     */
    public function getId(): int
    {
        return $this->id;
    }

    /**
     * @param int $id
     */
    public function setId(int $id): void
    {
        $this->id = $id;
    }

    /**
     * @return int
     */
    public function getTypeId(): int
    {
        return $this->type_id;
    }

    /**
     * @param int $typeId
     */
    public function setTypeId(int $typeId): void
    {
        $this->type_id = $typeId;
    }

    /**
     * @return string
     */
    public function getName(): string
    {
        return $this->name;
    }

    /**
     * @param string $name
     */
    public function setName(string $name): void
    {
        $this->name = $name;
    }
}