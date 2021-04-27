<?php

namespace App\Entity\Integrity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Doctrine\ORM\PersistentCollection;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;

/**
 * @ORM\Entity(repositoryClass="App\Repository\AccountRepository")
 * @ORM\Table(name="accounts")
 * @UniqueEntity(fields={"username","email", "phone_number"}, message="Username or Email or Phone Number already taken")
 */
//todo: create field user -> move all user data there
class Account implements \Serializable {
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;
    /**
     * @var AccountState
     *
     * @ORM\ManyToOne(targetEntity="App\Entity\Integrity\AccountState")
     * @ORM\JoinColumn(name="account_state_id", referencedColumnName="id")
     */
    private $state;
    /**
     * @var \App\Entity\Integrity\Analytics\Account[]|null
     *
     * @ORM\OneToMany(targetEntity="App\Entity\Integrity\Analytics\Account", mappedBy="owner", cascade={"persist"})
     */
    private $analytics;
    /**
     * @ORM\Column(type="text")
     */
    public $public_key;
    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $first_name;
    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $last_name;
    /**
     * @ORM\Column(type="string", length=255, unique=true)
     */
    private $username;
    /**
     * @ORM\Column(type="string", length=255)
     */
    private $password;
    /**
     * @ORM\Column(type="string", length=255, unique=true)
     */
    private $email;
    /**
     * @ORM\Column(type="string", nullable=TRUE)
     */
    private $avatar;
    /**
     * @ORM\Column(type="string", length=50, nullable=TRUE)
     */
    private $zoho_contact_id;
    /**
     * @ORM\Column(type="datetime", nullable=TRUE)
     */
    private $last_activity;
    /**
     * @ORM\Column(type="boolean", nullable=TRUE)
     */
    private $rates_in_usd;
    /**
     * @ORM\Column(type="string", length=50, unique=true)
     */
    private $phone_number;
    /**
     * @ORM\Column(type="string", length=250, nullable=TRUE)
     */
    private $keep_signed_key;

    /**
     * @ORM\Column(type="string", length=20, nullable=true)
     */
    private $binance_sub_account_id;

    /**
     * @ORM\Column(type="string", length=64, nullable=true)
     */
    private $binance_api_key;

    /**
     * @ORM\Column(type="string", length=64, nullable=true)
     */
    private $binance_api_secret;

    /**
     * @ORM\OneToMany(targetEntity=SumSubReview::class, mappedBy="account", orphanRemoval=true)
     */
    private $sumSubReviews;

    public function __construct()
    {
        $this->sumSubReviews = new ArrayCollection();
    }

    /**
     * @return int
     */
    public function getId(): int {
        return $this->id;
    }

    /**
     * @return Account[]|PersistentCollection
     */
    public function getAnalytics() {
        return $this->analytics;
    }

    /**
     * @param \App\Entity\Integrity\Analytics\Account $account
     */
    public function addAnalytics(\App\Entity\Integrity\Analytics\Account $account) {
        if (is_null($this->analytics)) {
            $this->analytics = new ArrayCollection();
        }
        $this->analytics->add($account);
    }

    /**
     * @param Account[]|PersistentCollection $analytics
     */
    public function setAnalytics($analytics): void {
        $this->analytics = $analytics;
    }

    /**
     * @return AccountState
     */
    public function getState(): AccountState{
        return $this->state;
    }

    /**
     * @param AccountState $state
     */
    public function setState(AccountState $state): void{
        $this->state = $state;
    }

    public function isUnconfirmed(): bool
    {
        return $this->getState()->getId() === AccountState::UNCONFIRMED;
    }

    public function isPending(): bool
    {
        return $this->getState()->getId() === AccountState::PENDING;
    }

    public function isApproved(): bool
    {
        return $this->getState()->getId() === AccountState::APPROVED;
    }
    /**
     * @return mixed
     */
    public function getPublicKey() {
        return $this->public_key;
    }

    /**
     * @param mixed $public_key
     */
    public function setPublicKey($public_key) {
        if (empty($this->public_key)) {
            $this->public_key = $public_key;
        }
    }

    /**
     * @return string
     */
    public function getUsername(): string {
        return $this->username;
    }

    /**
     * @param mixed $username
     */
    public function setUsername(string $username) {
        $this->username = $username;
    }

    /**
     * @return string
     */
    public function getPassword(): string {
        return $this->password;
    }

    /**
     * @param string $password
     */
    public function setPassword(string $password) {
        $this->password = $password;
    }

    /**
     * @return mixed
     */
    public function getAvatar() {
        return $this->avatar;
    }

    /**
     * @param mixed
     */
    public function setAvatar($avatar) {
        $this->avatar = $avatar;
    }

    /**
     * @return string|null
     */
    public function getZohoContactId(): ?string {
        return $this->zoho_contact_id;
    }

    /**
     * @param string $zoho_contact_id
     */
    public function setZohoContactId($zoho_contact_id) {
        $this->zoho_contact_id = $zoho_contact_id;
    }

    /**
     * @return \DateTime
     */
    public function getLastActivity(): \DateTime {
        return $this->last_activity;
    }

    /**
     * @param \DateTime $last_activity
     */
    public function setLastActivity(\DateTime $last_activity) {
        $this->last_activity = $last_activity;
    }

    /**
     * @return mixed
     */
    public function getFirstName() {
        return $this->first_name;
    }

    /**
     * @param mixed $first_name
     */
    public function setFirstName($first_name) {
        $this->first_name = $first_name;
    }

    /**
     * @return mixed
     */
    public function getLastName() {
        return $this->last_name;
    }

    /**
     * @param mixed $last_name
     */
    public function setLastName($last_name) {
        $this->last_name = $last_name;
    }

    public function getFullName() {
        if ( $this->getFirstName() && $this->getLastName() ) {
            return $this->getFirstName() . ' ' . $this->getLastName();
        }
        return $this->getUsername();
    }

    /**
     * @return mixed
     */
    public function getEmail() {
        return $this->email;
    }

    /**
     * @param mixed $email
     */
    public function setEmail($email) {
        $this->email = $email;
    }

    /**
     * @return bool
     */
    public function isRatesInUsd(): bool {
        return $this->rates_in_usd ?? false;
    }

    /**
     * @param bool $rates_in_usd
     * @return Account
     */
    public function setRatesInUsd(bool $rates_in_usd): Account {
        $this->rates_in_usd = $rates_in_usd;
        return $this;
    }

    /**
     * @return string
     */
    public function getPhoneNumber(): string {
        return $this->phone_number;
    }

    /**
     * @param string $phone_number
     * @return Account
     */
    public function setPhoneNumber(string $phone_number): Account {
        $this->phone_number = preg_replace('/\s/', '', $phone_number);
        return $this;
    }

    /**
     * @return string
     */
    public function getKeepSignedKey(): ?string {
        return $this->keep_signed_key;
    }

    /**
     * @param string $keep_signed_key
     * @return Account
     */
    public function setKeepSignedKey(?string $keep_signed_key): Account {
        $this->keep_signed_key = $keep_signed_key;
        return $this;
    }

    public function serialize() {
        return serialize([
            $this->id,
            $this->public_key,
            $this->first_name,
            $this->last_name,
            $this->username,
            $this->password,
            $this->email,
            $this->avatar,
            $this->zoho_contact_id,
            $this->last_activity,
            $this->rates_in_usd,
            $this->phone_number,
            $this->keep_signed_key,
            $this->binance_sub_account_id,
            $this->binance_api_key,
            $this->binance_api_secret,
        ]);
    }

    public function unserialize($serialized) {
        list(
            $this->id,
            $this->public_key,
            $this->first_name,
            $this->last_name,
            $this->username,
            $this->password,
            $this->email,
            $this->avatar,
            $this->zoho_contact_id,
            $this->last_activity,
            $this->rates_in_usd,
            $this->phone_number,
            $this->keep_signed_key,
            $this->binance_sub_account_id,
            $this->binance_api_key,
            $this->binance_api_secret
            ) = unserialize($serialized);
    }

    public function getBinanceSubAccountId(): ?string
    {
        return $this->binance_sub_account_id;
    }

    public function setBinanceSubAccountId(?string $binance_sub_account_id): self
    {
        $this->binance_sub_account_id = $binance_sub_account_id;

        return $this;
    }

    public function getBinanceApiKey(): ?string
    {
        return $this->binance_api_key;
    }

    public function setBinanceApiKey(?string $binance_api_key): self
    {
        $this->binance_api_key = $binance_api_key;

        return $this;
    }

    public function getBinanceApiSecret(): ?string
    {
        return $this->binance_api_secret;
    }

    public function setBinanceApiSecret(?string $binance_api_secret): self
    {
        $this->binance_api_secret = $binance_api_secret;

        return $this;
    }

    /**
     * @return Collection|SumSubReview[]
     */
    public function getSumSubReviews(): Collection
    {
        return $this->sumSubReviews;
    }

    public function addSumSubReview(SumSubReview $sumSubReview): self
    {
        if (!$this->sumSubReviews->contains($sumSubReview)) {
            $this->sumSubReviews[] = $sumSubReview;
            $sumSubReview->setAccount($this);
        }

        return $this;
    }

    public function removeSumSubReview(SumSubReview $sumSubReview): self
    {
        if ($this->sumSubReviews->removeElement($sumSubReview)) {
            // set the owning side to null (unless already changed)
            if ($sumSubReview->getAccount() === $this) {
                $sumSubReview->setAccount(null);
            }
        }

        return $this;
    }
}
