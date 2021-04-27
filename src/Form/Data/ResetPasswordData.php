<?php
namespace App\Form\Data;

use Symfony\Component\Validator\Constraints as Assert;
/**
 * @author: igor.popravka
 * Date: 28.02.2018
 * Time: 18:06
 */
class ResetPasswordData {
    /**
     * @Assert\NotBlank(message="Please type your Username")
     */
    private $for_username;

    /**
     * @return mixed
     */
    public function getForUsername() {
        return $this->for_username;
    }

    /**
     * @param mixed $for_username
     */
    public function setForUsername($for_username) {
        $this->for_username = $for_username;
    }
}