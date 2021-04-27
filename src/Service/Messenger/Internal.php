<?php

namespace App\Service\Messenger;

use App\Entity\Integrity\Account;
use App\Form\Data\SignUpData;
use App\Traits\MessageBusAwareTrait;
use App\Message\Internal\WelcomeEmailMessage;
use App\Message\Internal\PasswordResetEmailMessage;
use App\Message\Internal\ConfirmEmailAddressMessage;

/**
 * Class Internal
 */
class Internal
{
    use MessageBusAwareTrait;

    /**
     * Send the confirmation email
     *
     * @param Account $account
     *
     * @return void
     */
    public function confirmEmailAddress(Account $account): void
    {
        $this->bus->dispatch(new ConfirmEmailAddressMessage($account->getId()));
    }

    /**
     * Send the welcome email
     *
     * @param SignUpData $signUpData
     */
    public function sendWelcomeEmail(SignUpData $signUpData): void
    {
        $this->bus->dispatch(
            new WelcomeEmailMessage(
                $signUpData->getUsername(),
                $signUpData->getEmail(),
                $signUpData->getUsername(),
                $signUpData->getPassword()
            )
        );
    }

    /**
     * Send email to reset password
     *
     * @param Account $account
     */
    public function sendPasswordResetEmail(Account $account): void
    {
        $this->bus->dispatch(new PasswordResetEmailMessage($account->getId()));
    }
}