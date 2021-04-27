<?php

namespace App\Form\Data;

use Symfony\Component\Validator\Constraints as Assert;

/**
 * @author: igor.popravka
 * Date: 28.02.2018
 * Time: 18:06
 */
class ChangePasswordAPIData {
    /**
     * @Assert\NotBlank(message="Please type your new password")
     * @Assert\Length(min=8, max=20, minMessage="Min length should be 8 chars")
     * @Assert\Regex(
     *     pattern="/[A-Z]+/",
     *     message="Your new password should contains not less than one char in uppercase"
     * )
     */
    private $password;

    /**
     * @return mixed
     */
    public function getPassword() {
        return $this->password;
    }

    /**
     * @param mixed $password
     */
    public function setPassword($password) {
        $this->password = $password;
    }
}