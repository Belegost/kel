<?php

namespace App\Service\CustomValidator;

use Symfony\Component\Validator\Constraint;

/**
 * @Annotation
 */
class IsCanUseUsername extends Constraint
{
    public $message = 'User with username: "{{ string }}", already exist';

    /**
     * @return string
     */
    public function validatedBy()
    {
        return get_class($this).'Validator';
    }
}