<?php

namespace App\Form\Data;

use App\Entity\Integrity\Analytics\Exchange;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * Class AnalyticsData
 * @package App\Form\Data
 */
class AnalyticsData
{
    /**
     * @Assert\NotBlank(message="Please type APIkey")
     */
    private $APIkey;

    /**
     * @Assert\NotBlank(message="Please type APIsecret")
     */
    private $APIsecret;

    /**
     * @return mixed
     */
    public function getAPIkey()
    {
        return $this->APIkey;
    }

    /**
     * @param mixed $APIkey
     *
     * @return $this
     */
    public function setAPIkey($APIkey): self
    {
        $this->APIkey = $APIkey;

        return $this;
    }

    /**
     * @return mixed
     */
    public function getAPIsecret()
    {
        return $this->APIsecret;
    }

    /**
     * @param mixed $APIsecret
     *
     * @return $this
     */
    public function setAPIsecret($APIsecret): self
    {
        $this->APIsecret = $APIsecret;

        return $this;
    }

    public function handleAccount(\App\Entity\Integrity\Analytics\Account $account)
    {
        $account->setAPIkey($this->getAPIkey());
        $account->setAPIsecret($this->getAPIsecret());
    }
}