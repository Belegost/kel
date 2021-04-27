<?php

namespace App\Entity\Integrity\Analytics;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Table(name="analytics_exchange")
 * @ORM\Entity
 */
class Exchange
{
    /**
     * Bitfinex
     */
    const BITFINEX = 6;

    /**
     * @var int
     *
     * @ORM\Id
     * @ORM\Column(type="integer", nullable=false)
     * @ORM\GeneratedValue(strategy="IDENTITY")
     */
    protected $id;

    /**
     * @var string
     *
     * @ORM\Column(type="string", length=255, nullable=false)
     */
    protected $name;

    /**
     * @var string
     *
     * @ORM\Column(type="string", length=255, nullable=false)
     */
    protected $value;

    /**
     * @return int
     */
    public function getId(): int
    {
        return $this->id;
    }

    /**
     * @param int $id
     *
     * @return Exchange
     */
    public function setId(int $id): Exchange
    {
        $this->id = $id;

        return $this;
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
     *
     * @return Exchange
     */
    public function setName(string $name): Exchange
    {
        $this->name = $name;

        return $this;
    }

    /**
     * @return string
     */
    public function getValue(): string
    {
        return $this->value;
    }

    /**
     * @param string $value
     *
     * @return Exchange
     */
    public function setValue(string $value): Exchange
    {
        $this->value = $value;

        return $this;
    }
}