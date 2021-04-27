<?php

namespace App\Model;

/**
 * Class Google2FASettings
 */
class PersonalDataSettings extends AccountSettings
{
    const OPTION_IS_EMAIL_CONFIRMED = 6;

    /**
     * Checks if email was confirmed
     *
     * @return bool
     */
    public function isEmailConfirmed(): bool
    {
        return $this->get(static::OPTION_IS_EMAIL_CONFIRMED, false);
    }

    /**
     * Set Google 2FA Enabled
     *
     * @param bool $confirmed
     *
     * @return $this
     */
    public function setEmailConfirmed(bool $confirmed): PersonalDataSettings
    {
        $this->set(static::OPTION_IS_EMAIL_CONFIRMED, $confirmed);

        return $this;
    }
}
