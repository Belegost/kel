<?php
/**
 * Created by PhpStorm.
 * User: Grand
 * Date: 28.02.2018
 * Time: 22:53
 */

namespace App\Form\Data;

use Symfony\Component\Validator\Constraints as Assert;

class SignInData {
    /**
     * @Assert\NotBlank()
     */
    private $username;

    /**
     * @Assert\NotBlank()
     */
    private $password;

    /**
     * @Assert\NotNull()
     */
    private $keepsigned;

    public function __construct() {
        $this->keepsigned = true;
    }

    /**
     * @return mixed
     */
    public function getUsername() {
        return $this->username;
    }

    /**
     * @param mixed $username
     */
    public function setUsername($username): void {
        $this->username = $username;
    }

    /**
     * @return mixed
     */
    public function getPassword() {
        return $this->password;
    }

    /**
     * @param mixed $password
     */
    public function setPassword($password): void {
        $this->password = $password;
    }

    /**
     * @return mixed
     */
    public function getKeepsigned() {
        return $this->keepsigned;
    }

    /**
     * @param mixed $keep_signed
     */
    public function setKeepsigned($keep_signed): void {
        $this->keepsigned = $keep_signed;
    }
}