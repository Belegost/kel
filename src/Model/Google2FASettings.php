<?php

namespace App\Model;

use Google\Authenticator\GoogleQrUrl;
use Google\Authenticator\GoogleAuthenticator;

/**
 * Class Google2FASettings
 */
class Google2FASettings extends AccountSettings
{
    const OPTION_SECRET_KEY = 1;
    const OPTION_IS_ENABLED = 2;
    const OPTION_QR_URL = 3;
    const OPTION_RECOVERY_CODES = 4;
    const OPTION_SHOW_QR_URL = 5;

    /**
     * Checks if Google 2FA is Enabled
     *
     * @return bool
     */
    public function isGoogle2FAEnabled(): bool
    {
        return $this->get(static::OPTION_IS_ENABLED) !== false;
    }

    /**
     * Set Google 2FA Enabled
     *
     * @param bool $enabled
     *
     * @return $this
     */
    public function setGoogle2FAEnabled(bool $enabled): Google2FASettings
    {
        $notDefined = $this->get(static::OPTION_IS_ENABLED);
        if (is_null($notDefined)) {
            $this->set(static::OPTION_IS_ENABLED, $enabled);
        }

        return $this;
    }

    /**
     * Checks if Google 2FA is Show Qr Url
     *
     * @return bool
     */
    public function isGoogle2FAShowQrUrl(): bool
    {
        return $this->get(static::OPTION_SHOW_QR_URL, false);
    }

    /**
     * Set Google 2FA Show Qr Url
     *
     * @param bool $show
     *
     * @return $this
     */
    public function setGoogle2FAShowQrUrl(bool $show): Google2FASettings
    {
        $this->set(static::OPTION_SHOW_QR_URL, $show);

        return $this;
    }

    /**
     * Set the Google 2FA Secret Key
     *
     * @param string $value
     *
     * @return $this
     */
    public function setGoogle2FASecretKey(?string $value): Google2FASettings
    {
        $this->set(static::OPTION_SECRET_KEY, $value);

        return $this;
    }

    /**
     * Get the Google 2FA Secret Key
     *
     * @return string|null
     */
    public function getGoogle2FASecretKey(): ?string
    {
        return $this->get(static::OPTION_SECRET_KEY);
    }

    /**
     * Refresh the Google 2FA Secret Key
     *
     * @return Google2FASettings
     */
    public function refreshGoogle2FASecretKey(): Google2FASettings
    {
        return $this->setGoogle2FASecretKey((new GoogleAuthenticator())->generateSecret());
    }

    /**
     * Set the Google 2FA Qr Url
     *
     * @param string|null $url
     *
     * @return $this
     */
    public function setGoogle2FAQrUrl(?string $url): Google2FASettings
    {
        $this->set(static::OPTION_QR_URL, $url);

        return $this;
    }

    /**
     * Get the Google 2FA Qr Url
     *
     * @return string|null
     */
    public function getGoogle2FAQrUrl(): ?string
    {
        return $this->get(static::OPTION_QR_URL);
    }

    /**
     * Refresh the Google 2FA Qr Url
     *
     * @param string $account
     *
     * @return Google2FASettings
     */
    public function refreshGoogle2FAQrUrl(string $account): Google2FASettings
    {
        $url = GoogleQrUrl::generate($account, $this->getGoogle2FASecretKey(), getenv('APP_NAME'));

        return $this->setGoogle2FAQrUrl($url);
    }

    /**
     * Set the Google 2FA Recovery Codes
     *
     * @param array|null $codes
     *
     * @return $this
     */
    public function setGoogle2FARecoveryCodes(?array $codes): Google2FASettings
    {
        $this->set(static::OPTION_RECOVERY_CODES, $codes);

        return $this;
    }

    /**
     * Get the Google 2FA Recovery Codes
     *
     * @return array|null
     */
    public function getGoogle2FARecoveryCodes(): ?array
    {
        return $this->get(static::OPTION_RECOVERY_CODES, []);
    }

    /**
     * Refresh the Google 2FA Recovery Codes
     *
     * @return Google2FASettings
     */
    public function refreshGoogle2FARecoveryCodes(): Google2FASettings
    {
        $oldCodes = $this->getGoogle2FARecoveryCodes();

        do {
            $newCodes = array_map(
                function () {
                    return random_int(100000, 999999);
                },
                array_fill(0, 10, null)
            );
        } while (count(array_diff($newCodes, $oldCodes)) != 10);

        return $this->setGoogle2FARecoveryCodes($newCodes);
    }

    /**
     * Check Verification Code
     *
     * @param $code
     *
     * @return bool
     */
    public function checkGoogle2FAVerificationCode($code): bool
    {
        return (new GoogleAuthenticator())->checkCode($this->getGoogle2FASecretKey(),$code);
    }

    /**
     * Check Recovery Code
     *
     * @param $code
     *
     * @return bool
     */
    public function checkGoogle2FARecoveryCode($code): bool
    {
        return in_array($code, $this->getGoogle2FARecoveryCodes());
    }
}
