<?php

namespace App\Entity\Integrity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity()
 * @ORM\Table(name="accounts_settings")
 */
class AccountSetting
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private int $id;

    /**
     * @ORM\Column(type="integer", name="`account_id`")
     */
    private int $account_id;

    /**
     * @ORM\Column(type="integer", name="`setting_id`")
     */
    private string $setting_id;

    /**
     * @return int
     */
    public function getId(): int
    {
        return $this->id;
    }

    /**
     * @param int $id
     */
    public function setId(int $id): void
    {
        $this->id = $id;
    }

    /**
     * @return int
     */
    public function getAccountId(): int
    {
        return $this->account_id;
    }

    /**
     * @param int $accountId
     */
    public function setAccountId(int $accountId): void
    {
        $this->account_id = $accountId;
    }

    /**
     * @return string
     */
    public function getSettingId(): string
    {
        return $this->setting_id;
    }

    /**
     * @param string $settingId
     */
    public function setSettingId(string $settingId): void
    {
        $this->setting_id = $settingId;
    }
}