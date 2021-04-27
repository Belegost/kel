<?php

namespace App\Service\CustomValidator;

use Symfony\Component\Validator\Constraint;

/**
 * @Annotation
 */
class IsCanUseEmail extends Constraint
{
    public $message = 'User with email: "{{ string }}", already exist';

    /**
     * @return string
     */
    public function validatedBy()
    {
        return get_class($this).'Validator';
    }
}