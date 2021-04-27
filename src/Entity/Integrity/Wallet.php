<?php

namespace App\Entity\Integrity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\WalletRepository")
 * @ORM\Table(name="wallets")
 */
class Wallet {
    const TYPE_CREDIT_WALLET = 'CREDIT_WALLET';
    const TYPE_DEBIT_WALLET = 'DEBIT_WALLET';

    const STATUS_ACTIVE = 'ACTIVE';
    const STATUS_INACTIVE = 'INACTIVE';

    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=20)
     * @var string
     */
    private $account_id;

    /**
     * @ORM\Column(type="string", length=250)
     * @var string
     */
    private $name;

    /**
     * @ORM\Column(type="string", length=50, nullable=TRUE)
     * @var string
     */
    private $currency;

    /**
     * @ORM\Column(type="string", length=250)
     * @var string
     */
    private $type;

    /**
     * @ORM\Column(type="string", length=250)
     * @var string
     */
    private $address;

    /**
     * @ORM\Column(type="string", length=50)
     * @var string
     */
    private $status;

    /**
     * @ORM\Column(type="array")
     * @var array
     */
    private $operations;

    public function __construct() {
        $this->operations = [];
    }

    /**
     * @return mixed
     */
    public function getId() {
        return $this->id;
    }

    /**
     * @return string
     */
    public function getAccountId(): string {
        return $this->account_id;
    }

    /**
     * @param string $account_id
     * @return Wallet
     */
    public function setAccountId(string $account_id): Wallet {
        $this->account_id = $account_id;
        return $this;
    }

    /**
     * @return string
     */
    public function getName(): string {
        return $this->name;
    }

    /**
     * @param string $name
     * @return Wallet
     */
    public function setName(string $name): Wallet {
        $this->name = $name;
        return $this;
    }

    /**
     * @return string
     */
    public function getType(): string {
        return $this->type;
    }

    /**
     * @param string $type
     * @return Wallet
     */
    public function setType(string $type): Wallet {
        $this->type = $type;
        return $this;
    }

    /**
     * @return string
     */
    public function getAddress(): string {
        return $this->address;
    }

    /**
     * @param string $address
     * @return Wallet
     */
    public function setAddress(string $address): Wallet {
        $this->address = $address;
        return $this;
    }

    /**
     * @return array
     */
    public function getOperations(): array {
        return $this->operations;
    }

    /**
     * @param array $operations
     * @return Wallet
     */
    public function setOperations(array $operations): Wallet {
        $this->operations = $operations;
        return $this;
    }

    /**
     * @param array $data
     * @return Wallet
     */
    public function addOperation(array $data): Wallet {
        $this->operations[] = $data;
        return $this;
    }

    /**
     * @return string
     */
    public function getStatus(): string {
        return $this->status;
    }

    /**
     * @param string $status
     * @return Wallet
     */
    public function setStatus(string $status): Wallet {
        $this->status = $status;
        return $this;
    }

    /**
     * @return string
     */
    public function getCurrency(): string {
        return $this->currency;
    }

    /**
     * @param string $currency
     * @return Wallet
     */
    public function setCurrency(string $currency): Wallet {
        $this->currency = $currency;
        return $this;
    }
}
