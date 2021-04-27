<?php

namespace App\Form\Data;

use App\Entity\Integrity\Account;
use App\Entity\Zoho\Contact;
use Symfony\Component\Validator\Constraints as Assert;
use App\Service\CustomValidator;

/**
 * Class SignUpData
 * @package App\Form\Data
 * @CustomValidator\IsPasswordMatch
 */
class SignUpData
{

    /**
     * @Assert\NotBlank(message="Please type your Username")
     * @CustomValidator\IsCanUseUsername()
     */
    private $username;

    /**
     * @Assert\NotBlank(message="Please type your Password")
     * @Assert\Length(min=8, max=20, minMessage="Min length should be 8 chars")
     * @Assert\Regex(
     *     pattern="/[A-Z]+/",
     *     message="Your password should contains not less than one char in uppercase"
     * )
     */
    private $password;

    /**
     * @var string
     * @Assert\NotBlank(message="Please re type your Password")
     */
    private $repeatPassword;

//    /**
//     * @Assert\NotBlank(message="Please type your First Name")
//     */
//    private $first_name;
//
//    /**
//     * @Assert\NotBlank(message="Please type your Last Name")
//     */
//    private $last_name;

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
     * @CustomValidator\IsCanUsePhone()
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
     *
     */
    private $email_notify;

    /**
     *
     */
    private $phone_notify;

    /**
     *
     */
    private $avatar;

    public function __construct()
    {
        $this->email_notify = true;
        $this->phone_notify = true;
    }

    /**
     * @return mixed
     */
    public function getUsername()
    {
        return $this->username;
    }

    /**
     * @param mixed $username
     */
    public function setUsername($username): void
    {
        $this->username = $username;
    }

    /**
     * @return mixed
     */
    public function getPassword()
    {
        return $this->password;
    }

    /**
     * @param mixed $password
     */
    public function setPassword($password): void
    {
        $this->password = $password;
    }

//    /**
//     * @return mixed
//     */
//    public function getFirstName()
//    {
//        return $this->first_name;
//    }
//
//    /**
//     * @param mixed $first_name
//     */
//    public function setFirstName($first_name): void
//    {
//        $this->first_name = $first_name;
//    }

    /**
     * @return mixed
     */
    public function getLastName()
    {
        return $this->last_name;
    }

    /**
     * @param mixed $last_name
     */
    public function setLastName($last_name): void
    {
        $this->last_name = $last_name;
    }

    /**
     * @return mixed
     */
    public function getEmail()
    {
        return $this->email;
    }

    /**
     * @param mixed $email
     */
    public function setEmail($email): void
    {
        $this->email = $email;
    }

    /**
     * @return mixed
     */
    public function getEmailNotify()
    {
        return $this->email_notify;
    }

    /**
     * @param mixed $email_notify
     */
    public function setEmailNotify($email_notify): void
    {
        $this->email_notify = $email_notify;
    }

    /**
     * @return mixed
     */
    public function getPhone()
    {
        return $this->phone;
    }

    /**
     * @param mixed $phone
     */
    public function setPhone($phone): void
    {
        $this->phone = $phone;
    }

    /**
     * @return mixed
     */
    public function getPhoneNotify()
    {
        return $this->phone_notify;
    }

    /**
     * @param mixed $phone_notify
     */
    public function setPhoneNotify($phone_notify): void
    {
        $this->phone_notify = $phone_notify;
    }

    /**
     * @return mixed
     */
    public function getAvatar()
    {
        return $this->avatar;
    }

    /**
     * @param mixed $avatar
     */
    public function setAvatar($avatar): void
    {
        $this->avatar = $avatar;
    }

    /**
     * @return mixed
     */
    public function getUploadFile()
    {
        return $this->upload_file;
    }

    /**
     * @param mixed $upload_file
     */
    public function setUploadFile($upload_file): void
    {
        $this->upload_file = $upload_file;
    }

    public function handleAccount(Account $account)
    {
//        $account->setFirstName($this->getFirstName());
//        $account->setLastName($this->getLastName());
        $account->setUsername($this->getUsername());
        $account->setPassword($this->getPassword());
        $account->setEmail($this->getEmail());
        $account->setPhoneNumber($this->getPhone());
        $account->setAvatar($this->getAvatar());
        //$account->setGoogle2FAEnabled(true); todo: this method is not exist in account entity
    }

    public function handleZohoContact(Contact $contact)
    {
//        $contact['First_Name'] = $this->getFirstName();
//        $contact['Last_Name'] = $this->getLastName();
        $contact['Email'] = $this->getEmail();
//        $contact['Email Notify'] = $this->getEmailNotify();
        $contact['Phone'] = $this->getPhone();
//        $contact['Phone Notify'] = $this->getPhoneNotify();
        $contact->setAvatar($this->getAvatar());
    }

    /**
     * @return string
     */
    public function getRepeatPassword()
    {
        return $this->repeatPassword;
    }

    /**
     * @param string $repeatPassword
     * @return SignUpData
     */
    public function setRepeatPassword(string $repeatPassword)
    {
        $this->repeatPassword = $repeatPassword;
        return $this;
    }


}
