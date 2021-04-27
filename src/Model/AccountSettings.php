<?php

namespace App\Model;

use Doctrine\ORM\Query\Expr;
use App\Service\Model;
use App\Entity\Integrity\AccountSetting;
use App\Entity\Integrity\SettingOption;
use App\Entity\Integrity\Setting;
use App\Entity\Integrity\SettingOptionType as OptionType;

use Doctrine\ORM\EntityManager;
use Doctrine\ORM\NonUniqueResultException;

/**
 * Class AccountSettings
 */
class AccountSettings extends Model
{
    /**
     * @var int
     */
    protected int $accountId;

    /**
     * Getting option value
     *
     * @param int $optionId
     * @param null $default
     *
     * @return bool|int|mixed|string|null
     */
    public function get(int $optionId, $default = null)
    {
        /**
         * @var EntityManager $em
         */
        $em = $this->getDoctrine()->getManager();

        try {
            $result = $em->createQueryBuilder()
                ->select(['StOp.type_id', 'St.value'])
                ->from(AccountSetting::class, 'AcSt')
                ->leftJoin(Setting::class, 'St', Expr\Join::WITH, 'AcSt.setting_id=St.id')
                ->leftJoin(SettingOption::class, 'StOp', Expr\Join::WITH, 'St.option_id=StOp.id')
                ->where('AcSt.account_id = :accountId')
                ->andWhere('StOp.id = :optionId')
                ->setParameter('accountId', $this->accountId)
                ->setParameter('optionId', $optionId)
                ->getQuery()
                ->getOneOrNullResult();

            if (isset($result['type_id']) && isset($result['value'])) {
                $value = $this->decodeOptionValue($result['type_id'], $result['value']);
            }
        } catch (NonUniqueResultException $e) {
        }

        return $value ?? $default;
    }

    /**
     * Setting option value o remove
     *
     * @param int $optionId
     * @param null $value if NULL option will removed
     *
     * @return $this
     * @throws NonUniqueResultException
     * @throws \Doctrine\ORM\ORMException
     * @throws \Doctrine\ORM\OptimisticLockException
     * @throws \Doctrine\ORM\TransactionRequiredException
     */
    public function set(int $optionId, $value = null): self
    {
        /**
         * @var EntityManager $em
         */
        $em = $this->getDoctrine()->getManager();

        /**
         * @var SettingOption $settingOption
         */
        if (!($settingOption = $em->find(SettingOption::class, $optionId))) {
            return $this;
        }

        /**
         * @var AccountSetting $accountSetting
         */
        $accountSetting = $em->createQueryBuilder()
            ->select('AcSt')
            ->from(AccountSetting::class, 'AcSt')
            ->leftJoin(Setting::class, 'St', Expr\Join::WITH, 'AcSt.setting_id=St.id')
            ->leftJoin(SettingOption::class, 'StOp', Expr\Join::WITH, 'St.option_id=StOp.id')
            ->where('AcSt.account_id = :accountId')
            ->andWhere('StOp.id = :optionId')
            ->setParameter('accountId', $this->accountId)
            ->setParameter('optionId', $settingOption->getId())
            ->getQuery()
            ->getOneOrNullResult();

        $setting = $accountSetting ? $em->find(Setting::class, $accountSetting->getSettingId()) : null;

        $em->beginTransaction();
        try {
            if (is_null($value)) {
                if ($setting) {
                    $em->remove($setting);
                }

                if ($accountSetting) {
                    $em->remove($accountSetting);
                }

                $em->flush();
            } else {
                $value = $this->encodeOptionValue($settingOption->getTypeId(), $value);
                if (is_null($value)) {
                    return $this;
                }

                if (!$setting) {
                    $setting = new Setting();
                    $setting->setOptionId($settingOption->getId());
                    $setting->setValue($value);

                    $em->persist($setting);
                    $em->flush();

                    $accountSetting = new AccountSetting();
                    $accountSetting->setAccountId($this->accountId);
                    $accountSetting->setSettingId($setting->getId());
                    $em->persist($accountSetting);

                    $em->flush();
                } else if ($setting->getValue() != $value) {
                    $setting->setValue($value);

                    $em->persist($setting);
                    $em->flush();
                }
            }

            $em->commit();
        } catch (\Exception $e) {
            $em->rollback();
        }

        return $this;
    }

    /**
     * Encode option value according with option type
     *
     * @param int $typeId
     * @param $value
     *
     * @return false|int|string|null
     */
    protected function encodeOptionValue(int $typeId, $value)
    {
        switch ($typeId) {
            case OptionType::TYPE_INTEGER:
            case OptionType::TYPE_BOOLEAN:
                return (int)$value;
            case OptionType::TYPE_STRING:
                return (string)$value;
            case OptionType::TYPE_JSON:
                return json_encode($value);
        }

        return null;
    }

    /**
     * Decode option value according with option type
     *
     * @param int $typeId
     * @param $value
     *
     * @return bool|int|mixed|string|null
     */
    protected function decodeOptionValue(int $typeId, $value)
    {
        switch ($typeId) {
            case OptionType::TYPE_INTEGER:
                return (int)$value;
            case OptionType::TYPE_BOOLEAN:
                return (boolean)$value;
            case OptionType::TYPE_STRING:
                return (string)$value;
            case OptionType::TYPE_JSON:
                $data = json_decode($value, true);
                if (json_last_error() == JSON_ERROR_NONE) {
                    return $data;
                }
        }

        return null;
    }
}
