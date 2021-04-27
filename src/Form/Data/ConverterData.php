<?php


namespace App\Form\Data;

use Symfony\Component\Validator\Constraints as Assert;

class ConverterData
{

    /**
     * @var string
     * @Assert\NotBlank(message="Please choose a base currency.")
     */
    protected string $from;

    /**
     * @var string
     * @Assert\NotBlank(message="Please choose a second currency.")
     * @Assert\NotEqualTo(propertyPath="from")
     */
    protected string $to;

    /**
     * @var float
     * @Assert\NotBlank(message="Please enter a valid money amount.")
     * @Assert\Positive()
     */
    protected float $amount;

    /**
     * @return string
     */
    public function getFrom(): string
    {
        return $this->from;
    }

    /**
     * @param string $from
     */
    public function setFrom(string $from): void
    {
        $this->from = $from;
    }

    /**
     * @return string
     */
    public function getTo(): string
    {
        return $this->to;
    }

    /**
     * @param string $to
     */
    public function setTo(string $to): void
    {
        $this->to = $to;
    }

    /**
     * @return float
     */
    public function getAmount(): float
    {
        return $this->amount;
    }

    /**
     * @param float $amount
     */
    public function setAmount(float $amount): void
    {
        $this->amount = $amount;
    }


}
