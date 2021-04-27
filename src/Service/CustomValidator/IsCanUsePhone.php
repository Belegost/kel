<?php

namespace App\Service\CustomValidator;

use Symfony\Component\Validator\Constraint;

/**
 * @Annotation
 */
class IsCanUsePhone extends Constraint
{
    public $message = 'User with phone: "{{ string }}", already exist';

    /**
     * @return string
     */
    public function validatedBy()
    {
        return get_class($this).'Validator';
    }
}