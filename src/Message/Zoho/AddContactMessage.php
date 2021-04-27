<?php


namespace App\Message\Zoho;

/**
 * Class AddContact
 */
class AddContactMessage
{
    protected int $accountId;

    /**
     * AddContact constructor.
     *
     * @param int $accountId Reference to \App\Entity\Integrity\Account::$id
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