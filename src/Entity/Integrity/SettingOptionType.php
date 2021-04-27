<?php

namespace App\Entity\Integrity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;

/**
 * @ORM\Entity()
 * @ORM\Table(name="setting_option_types")
 * @UniqueEntity(fields={"`name`"}, message="The same name of the option type olready exist")
 */
class SettingOptionType
{
    CONST TYPE_INTEGER = 1;
    CONST TYPE_BOOLEAN = 2;
    CONST TYPE_STRING= 3;
    CONST TYPE_JSON = 4;

    /**
     * @ORM\Id
     * @ORM\Column(type="smallint")
     */
    private int $id;

    /**
     * @ORM\Column(type="string", name="`name`", length=64, unique=true)
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