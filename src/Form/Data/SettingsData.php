<?php

namespace App\Form\Data;

use App\Entity\Zoho\Contact as ZohoContact;
use Symfony\Component\Validator\Constraints as Assert;
use App\Service\CustomValidator;

/**
 * Class SettingsData
 * @package App\Form\Data
 */
class SettingsData {
    /**
     * @Assert\NotBlank(message="Please type your First Name")
     */
    private $first_name;

    /**
     * @Assert\NotBlank(message="Please type your Last Name")
     */
    private $last_name;

    /**
     * @Assert\NotBlank(message="Please type your Email")
     * @Assert\Email(message="Incorrect format of the Email address")
     * @CustomValidator\IsCanUseEmail()
     */
    private $email;

    /**
     * @Assert\NotBlank(message="Please type your Phone")
     * @Assert\Length(
     *     min=10,
     *     max=20,
     *     minMessage="Min length should be 10 chars",
     *     minMessage="Max length should be 20 chars"
     * )
     * @Assert\Regex(
     *     pattern="/[0-9+\(\)\s-]+/",
     *     message="Value contains unacceptable symbols"
     * )
     */
    private $phone;

    /**
     * @Assert\Image(
     *     maxSize = "5M",
     *     mimeTypes = {"image/jpeg", "image/gif", "image/png"},
     *     maxSizeMessage="Max size shouldn't be more than 5 Mb",
     *     mimeTypesMessage="Wrong file type of an avatar image"
     * )
     */
    private $upload_file;

    /**
     * @var mixed
     */
    private $country;

    /**
     * @var mixed
     */
    private $city;

    /**
     * @var mixed
     */
    private $state;

    /**
     * @var mixed
     */
    private $zip_code;

    /**
     * @var mixed
     */
    private $address;

    /**
     * @var mixed
     */
    private $avatar;

    /**
     * @var mixed
     */
    private $email_notify;

    /**
     * @var mixed
     */
    private $phone_notify;

    /**
     * SettingsData constructor.
     * @param ZohoContact|null $contact
     */
    public function __construct(ZohoContact $contact = null) {
        $this->email_notify = true;
        $this->phone_notify = true;

        if(isset($contact)){
            $this->initSettingsData($contact);
        }
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
    public function setFirstName($first_name): void {
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
    public function setLastName($last_name){
        $this->last_name = $last_name;
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
    public function setEmail($email){
        $this->email = $email;
    }

    /**
     * @return mixed
     */
    public function getEmailNotify() {
        return $this->email_notify;
    }

    /**
     * @param mixed $email_notify
     */
    public function setEmailNotify($email_notify){
        $this->email_notify = $email_notify;
    }

    /**
     * @return mixed
     */
    public function getPhone() {
        return $this->phone;
    }

    /**
     * @param mixed $phone
     */
    public function setPhone($phone){
        $this->phone = $phone;
    }

    /**
     * @return mixed
     */
    public function getPhoneNotify() {
        return $this->phone_notify;
    }

    /**
     * @param mixed $phone_notify
     */
    public function setPhoneNotify($phone_notify){
        $this->phone_notify = $phone_notify;
    }

    /**
     * @return mixed
     */
    public function getCountry() {
        return $this->country;
    }

    /**
     * @param mixed $country
     */
    public function setCountry($country){
        $this->country = $country;
    }

    /**
     * @return mixed
     */
    public function getCity() {
        return $this->city;
    }

    /**
     * @param mixed $city
     */
    public function setCity($city){
        $this->city = $city;
    }

    /**
     * @return mixed
     */
    public function getState() {
        return $this->state;
    }

    /**
     * @param mixed $state
     */
    public function setState($state){
        $this->state = $state;
    }

    /**
     * @return mixed
     */
    public function getZipCode() {
        return $this->zip_code;
    }

    /**
     * @param mixed $zip_code
     */
    public function setZipCode($zip_code){
        $this->zip_code = $zip_code;
    }

    /**
     * @return mixed
     */
    public function getAddress() {
        return $this->address;
    }

    /**
     * @param mixed $address
     */
    public function setAddress($address){
        $this->address = $address;
    }

    /**
     * @return mixed
     */
    public function getAvatar() {
        return $this->avatar;
    }

    /**
     * @param mixed $avatar
     */
    public function setAvatar($avatar){
        $this->avatar = $avatar;
    }

    /**
     * @return mixed
     */
    public function getUploadFile() {
        return $this->upload_file;
    }

    /**
     * @param mixed $upload_file
     */
    public function setUploadFile($upload_file){
        $this->upload_file = $upload_file;
    }

    public function handleZohoContact(ZohoContact $contact) {
        $contact->setFirstName($this->getFirstName());
        $contact->setLastName($this->getLastName());
        $contact->setEmail($this->getEmail());
        $contact->setEmailNotify($this->getEmailNotify());
        $contact->setPhone($this->getPhone());
        $contact->setPhoneNotify($this->getPhoneNotify());
        $contact->setCountry($this->getCountry());
        $contact->setCity($this->getCity());
        $contact->setState($this->getState());
        $contact->setZipCode($this->getZipCode());
        $contact->setAddress($this->getAddress());
        $contact->setAvatar($this->getAvatar());
    }

    protected function initSettingsData(ZohoContact $contact) {
        $this->setFirstName($contact->getFirstName());
        $this->setLastName($contact->getLastName());
        $this->setEmail($contact->getEmail());
        $this->setEmailNotify($contact->getEmailNotify());
        $this->setPhone($contact->getPhone());
        $this->setPhoneNotify($contact->getPhoneNotify());
        $this->setCountry($contact->getCountry());
        $this->setCity($contact->getCity());
        $this->setState($contact->getState());
        $this->setZipCode($contact->getZipCode());
        $this->setAddress($contact->getAddress());
    }
}