<?php

namespace App\Entity\Integrity\Analytics;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;
use Doctrine\ORM\PersistentCollection;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;

/**
 * @ORM\Entity(repositoryClass="App\Repository\Analytics\AccountRepository")
 * @ORM\Table(name="analytics_account")
 *
 * @UniqueEntity(fields={"exchange", "APIkey", "APIsecret"}, message="API key/secret pair have already been used in system")
 *
 * @todo rename to analytics
 */
class Account
{
    /**
     * @var int
     *
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    protected $id;

    /**
     * @var \App\Entity\Integrity\Account
     *
     * @ORM\ManyToOne(targetEntity="\App\Entity\Integrity\Account", inversedBy="analytics", cascade={"persist"})
     * @ORM\JoinColumn(name="owner_id", referencedColumnName="id")
     */
    protected $owner;

    /**
     * @var Exchange
     *
     * @ORM\ManyToOne(targetEntity="Exchange")
     * @ORM\JoinColumn(name="exchange_id", referencedColumnName="id")
     */
    protected $exchange;

    /**
     * @var Instrument[]|PersistentCollection
     *
     * @ORM\ManyToMany(targetEntity="Instrument", cascade={"persist"})
     * @ORM\JoinTable(name="account_to_instruments",
     *      joinColumns={@ORM\JoinColumn(name="account_id", referencedColumnName="id")},
     *      inverseJoinColumns={@ORM\JoinColumn(name="instrument_id", referencedColumnName="id")})
     */
    protected $instruments;

    /**
     * @var string
     *
     * @ORM\Column(type="string", length=255)
     */
    protected $APIkey = '';

    /**
     * @var string
     *
     * @ORM\Column(type="string", length=255)
     */
    protected $APIsecret = '';

    /**
     * @var bool
     *
     * @ORM\Column(type="boolean", nullable=false)
     */
    protected $isActive = true;

    /**
     * Account constructor
     */
    public function __construct()
    {
        $this->instruments = new ArrayCollection();
    }

    /**
     * @return string
     */
    public function getShortName()
    {
        return '...' . substr($this->getAPIkey(), -5);
    }

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
     * @return Account
     */
    public function setId(int $id): self
    {
        $this->id = $id;

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
     * @return Account
     */
    public function setExchange(Exchange $exchange): self
    {
        $this->exchange = $exchange;

        return $this;
    }

    /**
     * @return string
     */
    public function getAPIkey(): string
    {
        return $this->APIkey;
    }

    /**
     * @param string $APIkey
     *
     * @return Account
     */
    public function setAPIkey(string $APIkey): self
    {
        $this->APIkey = $APIkey;

        return $this;
    }

    /**
     * @return string
     */
    public function getAPIsecret(): string
    {
        return $this->APIsecret;
    }

    /**
     * @param string $APIsecret
     *
     * @return $this
     */
    public function setAPIsecret(string $APIsecret): self
    {
        $this->APIsecret = $APIsecret;

        return $this;
    }

    /**
     * @return bool
     */
    public function isActive(): bool
    {
        return $this->isActive;
    }

    /**
     * @param bool $isActive
     *
     * @return Account
     */
    public function setIsActive(bool $isActive): self
    {
        $this->isActive = $isActive;

        return $this;
    }

    /**
     * @return Instrument[]|null
     */
    public function getInstruments()
    {
        return $this->instruments;
    }

    /**
     * @param Instrument $instrument
     *
     * @return $this
     */
    public function addInstrument(Instrument $instrument): self
    {
        $this->instruments[] = $instrument;

        return $this;
    }

    /**
     * @param Instrument[]|null $instruments
     *
     * @return $this
     */
    public function setInstruments(array $instruments): self
    {
        $this->instruments = $instruments;

        return $this;
    }

    /**
     * @return \App\Entity\Integrity\Account
     */
    public function getOwner(): \App\Entity\Integrity\Account
    {
        return $this->owner;
    }

    /**
     * @param \App\Entity\Integrity\Account $owner
     *
     * @return $this
     */
    public function setOwner(\App\Entity\Integrity\Account $owner): self
    {
        $this->owner = $owner;

        return $this;
    }
}