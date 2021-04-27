<?php
/**
 * Created by PhpStorm.
 * User: Grand
 * Date: 28.02.2018
 * Time: 22:53
 */

namespace App\Form\Data;

use Symfony\Component\Validator\Constraints as Assert;

class Google2FAData {
    /**
     * @Assert\NotBlank()
     * @Assert\Length(
     *      min=6,
     *      max=6,
     *      minMessage="Verification Code must be at least {{ limit }} characters long",
     *      maxMessage="Verification Code cannot be longer than {{ limit }} characters"
     * )
     * @Assert\Type(
     *     type="numeric",
     *     message="Verification Code value should be of type {{ type }}"
     * )
     */
    private $verificationCode;

    /**
     * @return integer
     */
    public function getVerificationCode()
    {
        return $this->verificationCode;
    }

    /**
     * @param integer $verificationCode
     */
    public function setVerificationCode($verificationCode): void
    {
        $this->verificationCode = $verificationCode;
    }
}