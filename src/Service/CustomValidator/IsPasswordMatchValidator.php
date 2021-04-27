<?php

namespace App\Service\CustomValidator;

use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\ConstraintValidator;

/**
 * Class IsPasswordMatchValidator
 * @package App\Service\CustomValidator
 */
class IsPasswordMatchValidator extends ConstraintValidator
{
    /**
     * @param mixed $value
     * @param Constraint $constraint
     */
    public function validate($value, Constraint $constraint)
    {

        if ($value->getPassword() !== $value->getRepeatPassword()) {
            $this->context->buildViolation($constraint->message)
                ->atPath('repeatPassword')
                ->atPath('password')
                ->addViolation();
        }

    }

}