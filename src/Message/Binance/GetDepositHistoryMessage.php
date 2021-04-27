<?php


namespace App\Message\Binance;


class GetDepositHistoryMessage
{
    private string $subAccountId;

    /**
     * @return string
     */
    public function getSubAccountId(): string
    {
        return $this->subAccountId;
    }

    /**
     * @param string $subAccountId
     */
    public function setSubAccountId(string $subAccountId): void
    {
        $this->subAccountId = $subAccountId;
    }


}