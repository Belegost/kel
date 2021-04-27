<?php

namespace App\Message\Internal;

/**
 * Class WelcomeEmailMessage
 */
class WelcomeEmailMessage
{
    /**
     * @var string
     */
    protected string $clientName;

    /**
     * @var string
     */
    protected string $email;

    /**
     * @var string
     */
    protected string $login;

    /**
     * @var string
     */
    protected string $password;

    /**
     * WelcomeEmailMessage constructor.
     *
     * @param string $clientName
     * @param string $email
     * @param string $login
     * @param string $password
     */
    public function __construct(string $clientName, string $email, string $login, string $password)
    {
        $this->clientName = $clientName;
        $this->email = $email;
        $this->login = $login;
        $this->password = $password;
    }

    /**
     * @return string
     */
    public function getClientName(): string
    {
        return $this->clientName;
    }

    /**
     * @return string
     */
    public function getEmail(): string
    {
        return $this->email;
    }

    /**
     * @return string
     */
    public function getLogin(): string
    {
        return $this->login;
    }

    /**
     * @return string
     */
    public function getPassword(): string
    {
        return $this->password;
    }
}