<?php

namespace App\Entity\MRM;

use App\Repository\MRM\AssetTypeRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;

/**
 * @ORM\Entity(repositoryClass=AssetTypeRepository::class)
 * @ORM\Table(name="asset_types")
 * @UniqueEntity(fields={"code"}, message="Code should be unique")
 */
class AssetType
{
    const STATUS_ACTIVE = 1;
    const STATUS_DISABLED = 0;

    const VISIBLE_ON = 1;
    const VISIBLE_OFF = 0;

    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(name="code", type="string", length=50, unique=true)
     */
    private $code;

    /**
     * @ORM\Column(type="string", length=50)
     */
    private $label;

    /**
     * @ORM\Column(type="smallint")
     */
    private $status;

    /**
     * @ORM\Column(type="smallint")
     */
    private $visible;

    /**
     * @ORM\OneToMany(targetEntity=AssetHistory::class, mappedBy="asset_type", orphanRemoval=true)
     */
    private $assetHistory;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $logo;

    /**
     * @ORM\Column(type="float", nullable=true)
     */
    private $price;

    /**
     * @ORM\Column(type="datetime", nullable=true)
     */
    private $last_updated;

    /**
     * Asset constructor.
     */
    public function __construct() {
        $this->status = self::STATUS_ACTIVE;
        $this->assetHistory = new ArrayCollection();
    }

    public function getId() {
        return $this->id;
    }

    public function getCode(): ?string {
        return $this->code;
    }

    public function setCode(string $code): self {
        $this->code = $code;

        return $this;
    }

    public function getLabel(): ?string {
        return $this->label;
    }

    public function setLabel(string $label): self {
        $this->label = $label;

        return $this;
    }

    public function getStatus(): ?string {
        return $this->status;
    }

    public function setStatus(string $status): self {
        $this->status = $status;
        return $this;
    }

    /**
     * @return int
     */
    public function getVisible()
    {
        return $this->visible;
    }

    /**
     * @param int $visible
     */
    public function setVisible($visible): void
    {
        $this->visible = $visible;
    }

    public function toJSON() {
        return json_encode([
            'code' => $this->getCode(),
            'label' => $this->getLabel(),
            'status' => $this->getStatus(),
            'visible' => $this->getVisible()
        ]);
    }

    /**
     * @return Collection|AssetHistory[]
     */
    public function getAssetHistory(): Collection
    {
        return $this->assetHistory;
    }

    public function addAssetHistory(AssetHistory $assetHistory): self
    {
        if (!$this->assetHistory->contains($assetHistory)) {
            $this->assetHistory[] = $assetHistory;
            $assetHistory->setAssetType($this);
        }

        return $this;
    }

    public function removeAssetHistory(AssetHistory $assetHistory): self
    {
        if ($this->assetHistory->removeElement($assetHistory)) {
            // set the owning side to null (unless already changed)
            if ($assetHistory->getAssetType() === $this) {
                $assetHistory->setAssetType(null);
            }
        }

        return $this;
    }

    public function getLogo(): ?string
    {
        return $this->logo;
    }

    public function setLogo(?string $logo): self
    {
        $this->logo = $logo;

        return $this;
    }

    public function getPrice(): ?float
    {
        return $this->price;
    }

    public function setPrice(?float $price): self
    {
        $this->price = $price;

        return $this;
    }

    public function getLastUpdated(): ?\DateTimeInterface
    {
        return $this->last_updated;
    }

    public function setLastUpdated(?\DateTimeInterface $last_updated): self
    {
        $this->last_updated = $last_updated;

        return $this;
    }

}
