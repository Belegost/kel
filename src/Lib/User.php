<?php
/**
 * Created by PhpStorm.
 * User: Grand
 * Date: 10.05.2018
 * Time: 20:55
 */

namespace App\Lib;


use App\Entity\Integrity\Account;
use App\Service\CRM\IntegrityZohoClient;

class User {
    private $fieldsMap = [
        'id' => 'CONTACTID',
        'first_name' => 'First Name',
        'last_name' => 'Last Name',
        'phone' => 'Phone',
        'country' => 'Mailing Country',
        'city' => 'Mailing City',
        'state' => 'Mailing State',
        'zip_code' => 'Mailing Zip',
        'address' => 'Mailing Street'
    ];

    /** @var string */
    private $id;
    /** @var string */
    private $username;
    /** @var string */
    private $first_name;
    /** @var string */
    private $last_name;
    /** @var string */
    private $email;
    /** @var string */
    private $email_notify;
    /** @var string */
    private $timezone;
    /** @var string */
    private $phone;
    /** @var string */
    private $phone_notify;
    /** @var string */
    private $country;
    /** @var string */
    private $city;
    /** @var string */
    private $state;
    /** @var string */
    private $zip_code;
    /** @var string */
    private $address;
    /** @var string */
    private $avatar;
    /** @var string */
    private $photo;
    /** @var IntegrityZohoClient */
    private $zohoClient;
    /** @var DBClient */
    private $DBClient;
    /** @var bool */
    private $modified;

    /**
     * User constructor.
     * @param IntegrityZohoClient $zohoClient
     * @param DBClient $DBClient
     */
    public function __construct(IntegrityZohoClient $zohoClient, DBClient $DBClient) {
        $this->zohoClient = $zohoClient;
        $this->DBClient = $DBClient;
        $this->modified = false;
    }

    /**
     * @return string
     */
    public function getId(): ?string {
        return $this->id;
    }

    /**
     * @param $id
     * @return User
     */
    public function setId(?string $id): User {
        $this->id = $id;
        return $this;
    }

    /**
     * @return string
     * @return User
     */
    public function getUsername(): ?string {
        return $this->username;
    }

    /**
     * @param $username
     * @return User
     */
    public function setUsername(?string $username): User {
        $this->username = $username;
        return $this;
    }

    /**
     * @return string
     */
    public function getFirstName(): ?string {
        return $this->first_name;
    }

    /**
     * @param $first_name
     * @return User
     */
    public function setFirstName(?string $first_name): User {
        $this->first_name = $first_name;
        return $this;
    }

    /**
     * @return string
     */
    public function getLastName(): ?string {
        return $this->last_name;
    }

    /**
     * @param $last_name
     * @return User
     */
    public function setLastName(?string $last_name): User {
        $this->last_name = $last_name;
        return $this;
    }

    /**
     * @return string
     */
    public function getEmail(): ?string {
        return $this->email;
    }

    /**
     * @param $email
     * @return User
     */
    public function setEmail(?string $email): User {
        $this->email = $email;
        return $this;
    }

    /**
     * @return string
     */
    public function getEmailNotify(): ?string {
        return $this->email_notify;
    }

    /**
     * @param $email_notify
     * @return User
     */
    public function setEmailNotify(?string $email_notify): User {
        $this->email_notify = $email_notify;
        return $this;
    }

    /**
     * @return string
     */
    public function getTimezone(): ?string {
        return $this->timezone;
    }

    /**
     * @param $timezone
     * @return User
     */
    public function setTimezone(?string $timezone): User {
        $this->timezone = $timezone;
        return $this;
    }

    /**
     * @return string
     */
    public function getPhone(): ?string {
        return $this->phone;
    }

    /**
     * @param $phone
     * @return User
     */
    public function setPhone(?string $phone): User {
        $this->phone = $phone;
        return $this;
    }

    /**
     * @return string
     */
    public function getPhoneNotify(): ?string {
        return $this->phone_notify;
    }

    /**
     * @param $phone_notify
     * @return User
     */
    public function setPhoneNotify(?string $phone_notify): User {
        $this->phone_notify = $phone_notify;
        return $this;
    }

    /**
     * @return string
     */
    public function getCountry(): ?string {
        return $this->country;
    }

    /**
     * @param $country
     * @return User
     */
    public function setCountry(?string $country): User {
        $this->country = $country;
        return $this;
    }

    /**
     * @return string
     */
    public function getCity(): ?string {
        return $this->city;
    }

    /**
     * @param $city
     * @return User
     */
    public function setCity(?string $city): User {
        $this->city = $city;
        return $this;
    }

    /**
     * @return string
     */
    public function getState(): ?string {
        return $this->state;
    }

    /**
     * @param $state
     * @return User
     */
    public function setState(?string $state): User {
        $this->state = $state;
        return $this;
    }

    /**
     * @return string
     */
    public function getZipCode(): ?string {
        return $this->zip_code;
    }

    /**
     * @param $zip_code
     * @return User
     */
    public function setZipCode(?string $zip_code): User {
        $this->zip_code = $zip_code;
        return $this;
    }

    /**
     * @return string
     */
    public function getAddress(): ?string {
        return $this->address;
    }

    /**
     * @param $address
     * @return User
     */
    public function setAddress(?string $address): User {
        $this->address = $address;
        return $this;
    }

    /**
     * @return string
     */
    public function getAvatar(): ?string {
        return $this->avatar;
    }

    /**
     * @param $avatar
     * @return User
     */
    public function setAvatar(?string $avatar): User {
        $this->avatar = $avatar;
        return $this;
    }

    /**
     * @return string
     */
    public function getPhoto(): ?string {
        return $this->photo;
    }

    /**
     * @param $photo
     * @return User
     */
    public function setPhoto(?string $photo): User {
        $this->photo = $photo;
        return $this;
    }

    public function isRegistered() {
        return isset($this->id) && !empty($this->id);
    }

    public function fetchCrmData() {
        if ($this->isRegistered()) {
            $contactData = $this->zohoClient->findContactByEmail($this->getEmail());

            foreach ($this->fieldsMap as $userField => $contactField) {
                $contactVal = $contactData[$contactField] ?? null;
                if ($this->{$userField} != $contactVal) {
                    $this->{$userField} = $contactVal;
                    $this->modified = true;
                }
            }

            $photoPath = "{$this->zohoClient->getAvatarDir()}/{$this->getPhoto()}";

            if (!file_exists($photoPath)) {
                $contactPhoto = $this->zohoClient->downloadPhoto($this->getId());
                if ($this->getPhoto() !== $contactPhoto) {
                    $this->setPhoto($contactPhoto);
                    $this->modified = true;
                }
            }

            if ($this->modified) {
                $this->updateAccount();
            }
        }
    }

    public function updateAccount() {
        /** @var Account $account */
        $account = $this->DBClient
            ->getRepository(Account::class)
            ->findOneBy(['username' => $this->getUsername()]);
        $account->setUser(null);
        $this->DBClient->flushEntityObject($account);

        $account->setUser($this);
        $this->DBClient->flushEntityObject($account);
    }
}