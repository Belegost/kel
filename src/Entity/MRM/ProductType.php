<?php

namespace App\Entity\MRM;

use App\Lib\Assets;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;

/**
 * @ORM\Entity(repositoryClass="App\Repository\MRM\ProductTypeRepository")
 * @ORM\Table(name="product_types")
 * @UniqueEntity(fields={"code"}, message="Code should be unique")
 */
class ProductType {
    const STATUS_ACTIVE = 1;
    const STATUS_DISABLED = 0;

    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     * @var int
     */
    private $id;

    /**
     * @ORM\Column(name="code", type="string", length=50, unique=true)
     * @var string
     */
    private $code;

    /**
     * @ORM\Column(type="string", length=250)
     * @var string
     */
    private $name;

    /**
     * @ORM\Column(type="string", length=250)
     * @var float
     */
    private $cost;

    /**
     * @ORM\Column(type="string", length=5)
     * @var string
     */
    private $currency;

    /**
     * @ORM\Column(type="object", nullable=true)
     * @var Assets
     */
    private $assets;

    /**
     * @ORM\Column(type="text", nullable=true)
     * @var string
     */
    private $description;

    /**
     * @ORM\Column(type="datetime")
     * @var \DateTime
     */
    private $last_rebalanced;

    /**
     * @ORM\Column(type="datetime")
     * @var \DateTime
     */
    private $last_modified;

    /**
     * @ORM\Column(type="smallint")
     * @var string
     */
    private $status;

    /**
     * @ORM\Column(type="integer")
     * @var string
     */
    private $manager_id;

    /**
     * @ORM\Column(type="string", length=250, nullable=true)
     * @var string
     */
    private $zoho_id;

    public function __construct() {
        $this->last_rebalanced = new \DateTime();
        $this->last_modified = new \DateTime();
        $this->status = self::STATUS_DISABLED;
    }

    /**
     * @return int
     */
    public function getId(): int {
        return $this->id;
    }

    /**
     * @param int $id
     */
    public function setId(int $id): void {
        $this->id = $id;
    }

    /**
     * @return string
     */
    public function getCode(): string {
        return $this->code;
    }

    /**
     * @param string $code
     */
    public function setCode(string $code): void {
        $this->code = $code;
    }

    /**
     * @return string
     */
    public function getName(): string {
        return $this->name;
    }

    /**
     * @param string $name
     */
    public function setName(string $name): void {
        $this->name = $name;
    }

    /**
     * @return float
     */
    public function getCost(): float {
        return $this->cost;
    }

    /**
     * @param float $cost
     */
    public function setCost(float $cost): void {
        $this->cost = $cost;
    }

    /**
     * @return string
     */
    public function getCurrency(): string {
        return $this->currency;
    }

    /**
     * @param string $currency
     */
    public function setCurrency(string $currency): void {
        $this->currency = $currency;
    }

    public function getAssets(): ?Assets {
        return $this->assets;
    }

    /**
     * @param $assets
     */
    public function setAssets(?Assets $assets) {
        $this->assets = $assets;
    }

    /**
     * @return string
     */
    public function getDescription(): ?string {
        return $this->description;
    }

    /**
     * @param string $description
     */
    public function setDescription(?string $description): void {
        $this->description = $description;
    }

    /**
     * @return int
     */
    public function getStatus(): int {
        return $this->status;
    }

    /**
     * @param int $status
     */
    public function setStatus(int $status): void {
        $this->status = $status;
    }

    /**
     * @return string
     */
    public function getManagerId(): string {
        return $this->manager_id;
    }

    /**
     * @param string $manager_id
     */
    public function setManagerId(string $manager_id): void {
        $this->manager_id = $manager_id;
    }

    /**
     * @return string
     */
    public function getZohoId(): string {
        return $this->zoho_id;
    }

    /**
     * @param string $zoho_id
     */
    public function setZohoId(string $zoho_id) {
        $this->zoho_id = $zoho_id;
    }

    /**
     * @return \DateTime
     */
    public function getLastRebalanced(): \DateTime {
        return $this->last_rebalanced;
    }

    /**
     * @param \DateTime $last_rebalanced
     */
    public function setLastRebalanced(\DateTime $last_rebalanced): void {
        $this->last_rebalanced = $last_rebalanced;
    }

    /**
     * @return \DateTime
     */
    public function getLastModified(): \DateTime {
        return $this->last_modified;
    }

    /**
     * @param \DateTime $last_modified
     */
    public function setLastModified(\DateTime $last_modified): void {
        $this->last_modified = $last_modified;
    }
}
