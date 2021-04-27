<?php

namespace App\Entity\Zoho;

class Contact extends ZohoEntity {
    const STATUS_REGISTERED = 'registered';
    const STATUS_DEPOSITED = 'deposited';
    const STATUS_PRODUCT_PURCHASED = 'product purchased';
    const STATUS_PLAN_FINISHED = 'plan finished';
    const STATUS_REQUEST_WITHDRAWAL = 'request withdrawal';

    private $avatar;

    protected function build() {
        $this['Lead_Source'] = 'Integrity Fund';
    }

    /**
     * @return mixed
     */
    public function getAvatar() {
        return $this->avatar ?? null;
    }

    /**
     * @param mixed $avatar
     */
    public function setAvatar($avatar): void {
        $this->avatar = $avatar;
    }

    /**
     * @return bool
     */
    public function hasAvatar(): bool {
        return isset($this->avatar);
    }

    public function getFullName() {
        return $this['Full_Name'] ?? null;
    }

    /**
     * @return string
     */
    public function getId(): ?string {
        return $this['id'] ?? null;
    }

    /**
     * @param string $id
     * @return Contact
     */
    public function setId(string $id): Contact {
        $this['id'] = $id;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getFirstName() {
        return $this['First_Name'] ?? null;
    }

    /**
     * @param mixed $first_name
     */
    public function setFirstName($first_name): void {
        $this['First_Name'] = $first_name;
    }

    /**
     * @return mixed
     */
    public function getLastName() {
        return $this['Last_Name'] ?? null;
    }

    /**
     * @param mixed $last_name
     */
    public function setLastName($last_name): void {
        $this['Last_Name'] = $last_name;
    }

    /**
     * @return mixed
     */
    public function getEmail() {
        return $this->offsetExists('Email') ? $this['Email'] : null;
    }

    /**
     * @param mixed $email
     */
    public function setEmail($email): void {
        $this['Email'] = $email;
    }

    /**
     * @return mixed
     * todo: add new field to zoho contacts
     */
    public function getEmailNotify() {
        //return $this['Email Notify'];
        return true;
    }

    /**
     * @param mixed $email_notify
     */
    public function setEmailNotify($email_notify): void {
        $this['Email_Notify'] = $email_notify;
    }

    /**
     * @return mixed
     */
    public function getPhone() {
        return $this->offsetExists('Phone') ? $this['Phone'] : null;
    }

    /**
     * @param mixed $phone
     */
    public function setPhone($phone): void {
        $this['Phone'] = $phone;
    }

    /**
     * @return mixed
     * todo: add new field to zoho contacts
     */
    public function getPhoneNotify() {
        //return $this['Phone Notify'];
        return true;
    }

    /**
     * @param mixed $phone_notify
     */
    public function setPhoneNotify($phone_notify): void {
        $this['Phone_Notify'] = $phone_notify;
    }

    /**
     * @return mixed
     */
    public function getCountry() {
        return $this->offsetExists('Mailing_Country') ? $this['Mailing_Country'] : null;
    }

    /**
     * @param mixed $country
     */
    public function setCountry($country): void {
        $this['Mailing_Country'] = $country;
    }

    /**
     * @return mixed
     */
    public function getCity() {
        return $this->offsetExists('Mailing_City') ? $this['Mailing_City'] : null;
    }

    /**
     * @param mixed $city
     */
    public function setCity($city): void {
        $this['Mailing_City'] = $city;
    }

    /**
     * @return mixed
     */
    public function getState() {
        return $this->offsetExists('Mailing_State') ? $this['Mailing_State'] : null;
    }

    /**
     * @param mixed $state
     */
    public function setState($state): void {
        $this['Mailing_State'] = $state;
    }

    /**
     * @return mixed
     */
    public function getZipCode() {
        return $this->offsetExists('Mailing_Zip') ? $this['Mailing_Zip'] : null;
    }

    /**
     * @param mixed $zip_code
     */
    public function setZipCode($zip_code): void {
        $this['Mailing_Zip'] = $zip_code;
    }

    /**
     * @return mixed
     */
    public function getAddress() {
        return $this->offsetExists('Mailing_Street') ? $this['Mailing_Street'] : null;
    }

    /**
     * @param mixed $address
     */
    public function setAddress($address): void {
        $this['Mailing_Street'] = $address;
    }

    public function getIntegrityFundProfileLink(): ?string {
        return $this['Integrity_Fund_Profile_Link'] ?? null;
    }

    public function setIntegrityFundProfileLink($value): Contact {
        $this['Integrity_Fund_Profile_Link'] = $value;
        return $this;
    }

    public function getUsernameIntegrityFund(): ?string {
        return $this['Username_Integrity_Fund'] ?? null;
    }

    public function setUsernameIntegrityFund($value): Contact {
        $this['Username_Integrity_Fund'] = $value;
        return $this;
    }

    public function getIntegrityFundStatus(): ?string {
        return $this['Integrity_Fund_Status'] ?? null;
    }

    public function setIntegrityFundStatus($value): Contact {
        $this['Integrity_Fund_Status'] = $value;
        return $this;
    }
}
