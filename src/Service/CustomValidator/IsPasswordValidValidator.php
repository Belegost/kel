<?php

namespace App\Service\CustomValidator;

use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\ConstraintValidator;
use App\Service\Auth;

/**
 * Class IsPasswordValidValidator
 * @package App\Service\CustomValidator
 */
class IsPasswordValidValidator extends ConstraintValidator
{
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
     * @param mixed $value
     * @param Constraint $constraint
     */
    public function validate($value, Constraint $constraint)
    {
        if(!is_null($value)){
            if (!$this->auth->isValidPassword($value)) {
                $this->context->buildViolation($constraint->message)
                    ->addViolation();
            }
        }

    }
}