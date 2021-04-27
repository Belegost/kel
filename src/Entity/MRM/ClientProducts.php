<?php

namespace App\Entity\MRM;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\MRM\ClientProductsRepository")
 * @ORM\Table(name="client_products")
 */
class ClientProducts {
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=150, nullable=true)
     */
    private $order_id;

    /**
     * @ORM\Column(type="integer")
     */
    private $client_id;

    /**
     * @ORM\Column(type="string", length=150)
     */
    private $product_type_code;

    /**
     * @ORM\Column(type="string", length=50)
     */
    private $invest_amount;

    /**
     * @ORM\Column(type="string", length=10)
     */
    private $invest_currency;

    /**
     * @ORM\Column(type="integer")
     */
    private $quantity;

    /**
     * @ORM\Column(type="datetime")
     */
    private $creation_date;

    /**
     * @ORM\Column(type="string", length=50)
     */
    private $status;

    /**
     * @ORM\Column(type="integer")
     */
    private $manager_id;

    /**
     * @ORM\Column(type="datetime", nullable=true)
     */
    private $start_date;

    /**
     * @ORM\Column(type="datetime", nullable=true)
     */
    private $end_date;

    /**
     * ClientProducts constructor.
     */
    public function __construct() {
        $this->creation_date = new \DateTime();
    }

    public function getId() {
        return $this->id;
    }

    public function getOrderId(): ?string {
        return $this->order_id;
    }

    public function setOrderId(?string $order_id): self {
        $this->order_id = $order_id;

        return $this;
    }

    public function getClientId(): ?int {
        return $this->client_id;
    }

    public function setClientId(int $client_id): self {
        $this->client_id = $client_id;

        return $this;
    }

    public function getProductTypeCode(): ?string {
        return $this->product_type_code;
    }

    public function setProductTypeCode(string $product_type_code): self {
        $this->product_type_code = $product_type_code;

        return $this;
    }

    public function getInvestAmount(): ?string {
        return $this->invest_amount;
    }

    public function setInvestAmount(?string $invest_amount): self {
        $this->invest_amount = $invest_amount;

        return $this;
    }

    public function getInvestCurrency(): ?string {
        return $this->invest_currency;
    }

    public function setInvestCurrency(?string $invest_currency): self {
        $this->invest_currency = $invest_currency;

        return $this;
    }

    public function getQuantity(): ?int {
        return $this->quantity;
    }

    public function setQuantity(int $quantity): self {
        $this->quantity = $quantity;

        return $this;
    }

    public function getCreationDate(): ?\DateTimeInterface {
        return $this->creation_date;
    }

    public function setCreationDate(\DateTimeInterface $creation_date): self {
        $this->creation_date = $creation_date;

        return $this;
    }

    public function getStatus(): ?string {
        return $this->status;
    }

    public function setStatus(string $status): self {
        $this->status = $status;

        return $this;
    }

    public function getManagerId(): ?int {
        return $this->manager_id;
    }

    public function setManagerId(int $manager_id): self {
        $this->manager_id = $manager_id;

        return $this;
    }

    /**
     * @return \DateTimeInterface
     */
    public function getStartDate(): ?\DateTimeInterface {
        return $this->start_date;
    }

    /**
     * @param \DateTimeInterface $start_date
     * @return ClientProducts
     */
    public function setStartDate(\DateTimeInterface $start_date): self {
        $this->start_date = $start_date;
        return $this;
    }

    /**
     * @return \DateTimeInterface
     */
    public function getEndDate(): ?\DateTimeInterface {
        return $this->end_date;
    }

    /**
     * @param \DateTimeInterface $end_date
     * @return ClientProducts
     */
    public function setEndDate(\DateTimeInterface $end_date): self {
        $this->end_date = $end_date;
        return $this;
    }
}
