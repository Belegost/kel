<?php
namespace App\Form\Data;

use Symfony\Component\Validator\Constraints as Assert;
use App\Service\CustomValidator;

/**
 * Class ChangePasswordData
 * @package App\Form\Data
 * @CustomValidator\IsPasswordMatch
 */
class ChangePasswordData {
    /**
     * @Assert\NotBlank(message="Please type your current password")
     * @CustomValidator\IsPasswordValid
     */
    private $current_password;

    /**
     * @Assert\NotBlank(message="Please type your new Password")
     * @Assert\Length(min=8, max=20, minMessage="Min length should be 8 chars")
     * @Assert\Regex(
     *     pattern="/[A-Z]+/",
     *     message="Your password should contains not less than one char in uppercase"
     * )
     */
    private $password;

    /**
     * @var string
     * @Assert\NotBlank(message="Please re type your new Password")
     */
    private $repeatPassword;

    /**
     * @return mixed
     */
    public function getCurrentPassword()
    {
        return $this->current_password;
    }

    /**
     * @param mixed $current_password
     * @return ChangePasswordData
     */
    public function setCurrentPassword($current_password)
    {
        $this->current_password = $current_password;
        return $this;
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
     * @return ChangePasswordData
     */
    public function setPassword($password)
    {
        $this->password = $password;
        return $this;
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
     * @return ChangePasswordData
     */
    public function setRepeatPassword(string $repeatPassword)
    {
        $this->repeatPassword = $repeatPassword;
        return $this;
    }


}