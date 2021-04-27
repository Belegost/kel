<?php

namespace App\Entity\MRM;

use App\Repository\MRM\AssetHistoryRepository;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=AssetHistoryRepository::class)
 * @ORM\Table(name="asset_history")
 */
class AssetHistory
{
    public const TIMEFRAME_DAY = 1;
    public const TIMEFRAME_HOUR = 2;

    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\ManyToOne(targetEntity=AssetType::class, inversedBy="assetHistory")
     * @ORM\JoinColumn(nullable=false)
     */
    private $asset_type;

    /**
     * @ORM\Column(type="float")
     */
    private $high;

    /**
     * @ORM\Column(type="float")
     */
    private $low;

    /**
     * @ORM\Column(type="float")
     */
    private $open;

    /**
     * @ORM\Column(type="float")
     */
    private $close;

    /**
     * @ORM\Column(type="datetime")
     */
    private $datetime;

    /**
     * @ORM\Column(type="smallint")
     */
    private $timeframe;


    public function getId(): ?int
    {
        return $this->id;
    }

    public function getAssetType(): ?AssetType
    {
        return $this->asset_type;
    }

    public function setAssetType(?AssetType $asset_type): self
    {
        $this->asset_type = $asset_type;

        return $this;
    }

    public function getHigh(): ?float
    {
        return $this->high;
    }

    public function setHigh(float $high): self
    {
        $this->high = $high;

        return $this;
    }

    public function getLow(): ?float
    {
        return $this->low;
    }

    public function setLow(float $low): self
    {
        $this->low = $low;

        return $this;
    }

    public function getOpen(): ?float
    {
        return $this->open;
    }

    public function setOpen(float $open): self
    {
        $this->open = $open;

        return $this;
    }

    public function getClose(): ?float
    {
        return $this->close;
    }

    public function setClose(float $close): self
    {
        $this->close = $close;

        return $this;
    }

    public function getDatetime(): ?\DateTimeInterface
    {
        return $this->datetime;
    }

    public function setDatetime(\DateTimeInterface $datetime): self
    {
        $this->datetime = $datetime;

        return $this;
    }

    public function getTimeframe(): ?int
    {
        return $this->timeframe;
    }

    public function setTimeframe(int $timeframe): self
    {
        $this->timeframe = $timeframe;

        return $this;
    }
}
