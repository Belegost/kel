<?php

namespace App\Service\Messenger;

use App\Entity\Integrity\Account;
use App\Message\Zoho\AddContactMessage;
use App\Message\Zoho\UpdateContactMessage;
use App\Traits\MessageBusAwareTrait;

class Zoho
{
    use MessageBusAwareTrait;

    /**
     *
     * @param Account $account
     *
     * @return void
     */
    public function addContact(Account $account): void
    {
        $this->bus->dispatch(new AddContactMessage($account->getId()));
    }

    public function updateContact(Account $account): void
    {
        $this->bus->dispatch(new UpdateContactMessage($account->getId()));
    }
}
