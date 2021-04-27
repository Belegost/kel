<?php

namespace App\Message\Internal;

/**
 * Class ConfirmEmailAddressMessage
 */
class ConfirmEmailAddressMessage
{
    /**
     * @var int
     */
    protected int $accountId;

    /**
     * ConfirmEmailAddressMessage constructor.
     *
     * @param int $accountId
     */
    public function __construct(int $accountId)
    {
        $this->accountId = $accountId;
    }

    /**
     * @return int
     */
    public function getAccountId(): int
    {
        return $this->accountId;
    }
}