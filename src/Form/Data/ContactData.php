<?php


namespace App\Form\Data;

use Symfony\Component\Validator\Constraints as Assert;

class ContactData
{
    /**
     * @var string
     * @Assert\NotBlank(message="Please type your First Name")
     */
    private $username;
    /**
     * @var string
     * @Assert\NotBlank(message="Please type your Email")
     * @Assert\Email(message="Incorrect format of the Email address")
     */
    private $email;

    /**
     * @var string
     * @Assert\NotBlank(message="Please type your phone number")
     */
    private $phone;

    /**
     * @var string
     * @Assert\NotBlank(message="Please type Message")
     */
    private $message;

    /**
     * @return mixed
     */
    public function getUsername()
    {
        return $this->username;
    }

    /**
     * @param mixed $username
     * @return ContactData
     */
    public function setUsername($username)
    {
        $this->username = $username;
        return $this;
    }

    /**
     * @return string
     */
    public function getEmail()
    {
        return $this->email;
    }

    /**
     * @param string $email
     * @return ContactData
     */
    public function setEmail(string $email)
    {
        $this->email = $email;
        return $this;
    }

    /**
     * @return string
     */
    public function getMessage()
    {
        return $this->message;
    }

    /**
     * @param string $message
     * @return ContactData
     */
    public function setMessage(string $message)
    {
        $this->message = $message;
        return $this;
    }

    /**
     * @return string
     */
    public function getPhone()
    {
        return $this->phone;
    }

    /**
     * @param string $phone
     * @return ContactData
     */
    public function setPhone(string $phone)
    {
        $this->phone = $phone;
        return $this;
    }


}