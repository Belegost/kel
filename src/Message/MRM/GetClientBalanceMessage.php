<?php

namespace App\Message\MRM;

class GetClientBalanceMessage
{

    private ?string $subAccountId;

    /**
     * @return string|null
     */
    public function getSubAccountId(): ?string
    {
        return $this->subAccountId;
    }

    /**
     * @param string|null $subAccountId
     */
    public function setSubAccountId(?string $subAccountId): void
    {
        $this->subAccountId = $subAccountId;
    }

}
