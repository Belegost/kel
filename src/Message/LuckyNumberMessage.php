<?php

namespace App\Message;

class LuckyNumberMessage
{

    protected ?int $number;

    public function setNumber(int $number): void
    {
        $this->number = $number;
    }

    public function getNumber(): int
    {
        return $this->number;
    }

}
