<?php

namespace App\Service\CustomValidator;

use App\Service\Auth;
use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\ConstraintValidator;
use Doctrine\ORM\EntityManager;
use App\Entity\Integrity\Account;

/**
 * Class IsCanUseEmailValidator
 * @package App\Service\CustomValidator
 */
class IsCanUseEmailValidator extends ConstraintValidator
{
    /**
     * @var EntityManager
     */
    private $entityManager;

    /**
     * @var Auth
     */
    private $auth;

    /**
     * IsPasswordValidValidator constructor.
     * @param Auth $auth
     */
    public function __construct(Auth $auth)
    {
        $this->auth = $auth;
    }

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
        if ($this->auth->isLogged() && $this->auth->getAccount()->getEmail() == $value) {
            return;
        }

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
        return $repo->findOneBy(['email' => $value]);
    }

}
