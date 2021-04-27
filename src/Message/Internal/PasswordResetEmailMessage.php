<?php

namespace App\Message\Internal;

/**
 * Class ResetPasswordEmailMessage
 */
class PasswordResetEmailMessage
{
    /**
     * @var int
     */
    protected int $accountId;

    /**
     * ResetPasswordEmailMessage constructor.
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