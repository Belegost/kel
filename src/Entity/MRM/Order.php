<?php

namespace App\Entity\MRM;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints\DateTime;

/**
 * @ORM\Entity(repositoryClass="App\Repository\MRM\OrderRepository")
 * @ORM\Table(name="orders")
 */
class Order {
    const STATUS_PENDING = 'Pending';
    const STATUS_CANCELED = 'Canceled';
    const STATUS_EXECUTION = 'Execution';
    const STATUS_FINISHED = 'Finished';
    const STATUS_TAKEN = 'Taken';

    const TYPE_BUY = 'Buy';
    const TYPE_SALE = 'Sale';

    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=150, unique=true)
     */
    private $zoho_order_id;

    /**
     * @ORM\Column(type="string", length=50)
     */
    private $type;

    /**
     * @ORM\Column(type="datetime")
     */
    private $created;

    /**
     * @ORM\Column(type="integer")
     */
    private $client_id;

    /**
     * @ORM\Column(type="string")
     */
    private $product_type_code;

    /**
     * @ORM\Column(type="integer")
     */
    private $quantity;

    /**
     * @ORM\Column(type="string", length=50)
     */
    private $status;

    /**
     * @ORM\Column(type="integer", nullable=true)
     */
    private $manager_id;

    /**
     * @ORM\Column(type="boolean", nullable=true)
     */
    private $block_change;

    /**
     * @ORM\Column(type="datetime", nullable=true)
     */
    private $modified;

    /**
     * Order constructor.
     */
    public function __construct() {
        $this->created = new \DateTime();
        $this->modified = new \DateTime();
        $this->status = self::STATUS_PENDING;
        $this->type = self::TYPE_BUY;
        $this->block_change = false;
    }

    /**
     * @return mixed
     */
    public function getId() {
        return $this->id;
    }

    /**
     * @param mixed $id
     */
    public function setId($id): void {
        $this->id = $id;
    }

    /**
     * @return mixed
     */
    public function getType() {
        return $this->type;
    }

    /**
     * @param mixed $type
     */
    public function setType($type): void {
        $this->type = $type;
    }

    /**
     * @return mixed
     */
    public function getCreated() {
        return $this->created;
    }

    /**
     * @param mixed $created
     */
    public function setCreated($created): void {
        $this->created = $created;
    }

    /**
     * @return mixed
     */
    public function getClientId() {
        return $this->client_id;
    }

    /**
     * @param mixed $client_id
     */
    public function setClientId($client_id): void {
        $this->client_id = $client_id;
    }

    /**
     * @return mixed
     */
    public function getProductTypeCode() {
        return $this->product_type_code;
    }

    /**
     * @param mixed $product_type_code
     */
    public function setProductTypeCode($product_type_code): void {
        $this->product_type_code = $product_type_code;
    }

    /**
     * @return mixed
     */
    public function getQuantity() {
        return $this->quantity;
    }

    /**
     * @param mixed $quantity
     */
    public function setQuantity($quantity): void {
        $this->quantity = $quantity;
    }

    /**
     * @return mixed
     */
    public function getStatus() {
        return $this->status;
    }

    /**
     * @param mixed $status
     */
    public function setStatus($status): void {
        $this->status = $status;
    }

    /**
     * @return mixed
     */
    public function getManagerId() {
        return $this->manager_id;
    }

    /**
     * @param mixed $manager_id
     */
    public function setManagerId($manager_id): void {
        $this->manager_id = $manager_id;
    }

    /**
     * @return mixed
     */
    public function getZohoOrderId() {
        return $this->zoho_order_id;
    }

    /**
     * @param mixed $zoho_order_id
     */
    public function setZohoOrderId($zoho_order_id): void {
        $this->zoho_order_id = $zoho_order_id;
    }

    /**
     * @return mixed
     */
    public function getBlockChange() {
        return !!$this->block_change;
    }

    /**
     * @param mixed $block_change
     */
    public function setBlockChange($block_change) {
        $this->block_change = $block_change;
    }

    /**
     * @return \DateTime|null
     */
    public function getModified(): ?\DateTime {
        return $this->modified;
    }

    /**
     * @param \DateTime $modified
     */
    public function setModified(\DateTime $modified) {
        $this->modified = $modified;
    }
}
