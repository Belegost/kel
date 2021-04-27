<?php

namespace App\Entity\Integrity\Analytics;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Table(name="analytics_instrument")
 * @ORM\Entity
 *
 * @todo add composite key constraint for sort, exchange_id
 */
class Instrument
{
    /**
     * Bitcoin
     *
     * @todo move to asset
     */
    const BTC = 'BTC';
    const USD = 'USD';

    /**
     * @var int
     *
     * @ORM\Id
     * @ORM\Column(type="integer", nullable=false)
     * @ORM\GeneratedValue(strategy="IDENTITY")
     */
    protected $id;

    /**
     * @var Exchange
     *
     * @ORM\ManyToOne(targetEntity="Exchange")
     * @ORM\JoinColumn(name="exchange_id", referencedColumnName="id")
     */
    protected $exchange;

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
     * @var int
     *
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    protected $sort;

    /**
     * @var string
     *
     * @ORM\Column(type="integer", nullable=false)
     */
    protected $round = 0;

    /**
     * @var string
     *
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    protected $lastPrice;

    /**
     * @var string
     *
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    protected $lastClose;

//    /**
//     * @var string
//     *
//     * @ORM\Column(type="integer", nullable=false)
//     */
//    protected $active = 0;

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
     * @return Instrument
     */
    public function setId(int $id): Instrument
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
     * @return Instrument
     */
    public function setName(string $name): Instrument
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
     * @return Instrument
     */
    public function setValue(string $value): Instrument
    {
        $this->value = $value;

        return $this;
    }

    /**
     * @return Exchange
     */
    public function getExchange(): Exchange
    {
        return $this->exchange;
    }

    /**
     * @param Exchange $exchange
     *
     * @return Instrument
     */
    public function setExchange(Exchange $exchange): Instrument
    {
        $this->exchange = $exchange;

        return $this;
    }

    /**
     * @return string
     */
    public function getRound(): string
    {
        return $this->round;
    }

    /**
     * @param string $round
     *
     * @return Instrument
     */
    public function setRound(string $round): Instrument
    {
        $this->round = $round;

        return $this;
    }

    /**
     * @return int
     */
    public function getSort(): int
    {
        return $this->sort;
    }

    /**
     * @param int $sort
     *
     * @return Instrument
     */
    public function setSort(int $sort): Instrument
    {
        $this->sort = $sort;

        return $this;
    }

    /**
     * @return string
     */
    public function getLastPrice(): string
    {
        return $this->lastPrice;
    }

    /**
     * @param string $lastPrice
     *
     * @return Instrument
     */
    public function setLastPrice(string $lastPrice): Instrument
    {
        $this->lastPrice = $lastPrice;

        return $this;
    }

    /**
     * @return string
     */
    public function getLastClose(): string
    {
        return $this->lastClose;
    }

    /**
     * @param string $lastClose
     *
     * @return $this
     */
    public function setLastClose(string $lastClose): Instrument
    {
        $this->lastClose = $lastClose;

        return $this;
    }
}