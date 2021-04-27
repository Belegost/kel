<?php

namespace App\Service\CustomValidator;

use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\ConstraintValidator;
use Doctrine\ORM\EntityManager;
use App\Entity\Integrity\Account;

/**
 * Class IsCanUsePhoneValidator
 * @package App\Service\CustomValidator
 */
class IsCanUsePhoneValidator extends ConstraintValidator
{
    /**
     * @var EntityManager
     */
    private $entityManager;

    /**
     * @param EntityManager $entityManager
     */
    public function setEntityManager(EntityManager $entityManager)
    {
        $this->entityManager = $entityManager;
    }

    /**
     * @param mixed $value
     * @param Constraint $constraint
     */
    public function validate($value, Constraint $constraint)
    {
        $data = $this->findData($value);
        if (!is_null($data)) {
            $this->context->buildViolation($constraint->message)
                ->setParameter('{{ string }}', $value)
                ->addViolation();
        }
    }


    /**
     * @param $value
     * @return Account|null|object
     */
    private function findData($value)
    {
        $repo = $this->entityManager->getRepository(Account::class);
        return $repo->findOneBy(['phone_number' => $value]);
    }
}