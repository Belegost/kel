<?php

namespace App\Entity\Integrity\Analytics;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;
use Doctrine\ORM\PersistentCollection;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;

/**
 * @ORM\Entity(repositoryClass="App\Repository\Analytics\AssetRepository")
 * @ORM\Table(name="analytics_asset")
 *
 * @UniqueEntity(fields={"name"}, message="Asset has already been added")
 */
class Asset
{
    /**
     * Asset types
     */
    const BITCOIN = 1;
    const BITCOIN_CASH = 2;
    const DASH = 3;
    const ETHEREUM = 4;
    const IOTA = 5;
    const LITECOIN = 6;
    const MONERO = 7;
    const NEM = 8;
    const NEO = 9;
    const RIPPLE = 11;

    /**
     * Other types
     */
    const OTHERS = 10;

    /**
     * @var int
     *
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
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
    protected $fullName;

    /**
     * @var Instrument[]|PersistentCollection
     *
     * @ORM\OneToMany(targetEntity="App\Entity\Integrity\Analytics\Capitalization", mappedBy="asset", cascade={"persist"})
     */
    protected $capitalization;

    /**
     * Account constructor
     */
    public function __construct()
    {
        $this->capitalization = new ArrayCollection();
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
     * @return $this
     */
    public function setId(int $id): self
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
     * @return string
     */
    public function getFullName(): string
    {
        return $this->fullName;
    }

    /**
     * @param string $fullName
     *
     * @return $this
     */
    public function setFullName(string $fullName): self
    {
        $this->fullName = $fullName;

        return $this;
    }

    /**
     * @param string $name
     *
     * @return $this
     */
    public function setName(string $name): self
    {
        $this->name = $name;

        return $this;
    }

    /**
     * @return Instrument[]|PersistentCollection
     */
    public function getCapitalization()
    {
        return $this->capitalization;
    }

    /**
     * @param Instrument[]|PersistentCollection $capitalization
     *
     * @return $this
     */
    public function setCapitalization($capitalization): self
    {
        $this->capitalization = $capitalization;

        return $this;
    }
}