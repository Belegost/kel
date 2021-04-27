<?php

namespace App\Service\CustomValidator;

use Symfony\Component\Validator\Constraint;

/**
 * @Annotation
 */
class IsPasswordMatch extends Constraint
{
    public $message = 'Password must match!';

    /**
     * @return string
     */
    public function validatedBy()
    {
        return get_class($this) . 'Validator';
    }

    /**
     * @return string
     */
    public function getTargets()
    {
        return self::CLASS_CONSTRAINT;
    }
}