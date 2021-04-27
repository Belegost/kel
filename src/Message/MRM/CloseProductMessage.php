<?php

namespace App\Message\MRM;

class CloseProductMessage
{
    private ?int $clientProductId;

    /**
     * @return int|null
     */
    public function getClientProductId(): ?int
    {
        return $this->clientProductId;
    }

    /**
     * @param int|null $clientProductId
     */
    public function setClientProductId(?int $clientProductId): void
    {
        $this->clientProductId = $clientProductId;
    }


}
