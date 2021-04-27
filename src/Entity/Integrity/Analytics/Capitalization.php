<?php

namespace App\Entity\Integrity\Analytics;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;
use Doctrine\ORM\PersistentCollection;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;

/**
 * @ORM\Entity(repositoryClass="App\Repository\Analytics\CapitalizationRepository")
 * @ORM\Table(name="analytics_capitalization")
 *
 * @UniqueEntity(fields={"createdDate", "asset"}, message="Data for current asset by date has already been added")
 */
class Capitalization
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
     * @var \DateTime
     *
     * @ORM\Column(type="datetime")
     */
    protected $createdDate;

    /**
     * @var string
     *
     * @ORM\Column(type="string", length=255)
     */
    protected $value;

    /**
     * @var string
     *
     * @ORM\Column(type="string", length=255)
     */
    protected $percent;

    /**
     * @var Asset
     *
     * @ORM\ManyToOne(targetEntity="App\Entity\Integrity\Analytics\Asset", inversedBy="capitalization", cascade={"persist"})
     * @ORM\JoinColumn(name="asset_id", referencedColumnName="id")
     */
    protected $asset;

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
     * @return \DateTime
     */
    public function getCreatedDate(): \DateTime
    {
        return $this->createdDate;
    }

    /**
     * @param \DateTime $createdDate
     *
     * @return $this
     */
    public function setCreatedDate(\DateTime $createdDate): self
    {
        $this->createdDate = $createdDate;

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
     * @return $this
     */
    public function setValue(string $value): self
    {
        $this->value = $value;

        return $this;
    }

    /**
     * @return string
     */
    public function getPercent(): string
    {
        return $this->percent;
    }

    /**
     * @param string $percent
     *
     * @return $this
     */
    public function setPercent(string $percent): self
    {
        $this->percent = $percent;

        return $this;
    }

    /**
     * @return Asset
     */
    public function getAsset(): Asset
    {
        return $this->asset;
    }

    /**
     * @param Asset $asset
     *
     * @return $this
     */
    public function setAsset(Asset $asset): self
    {
        $this->asset = $asset;

        return $this;
    }
}