<?php

namespace App\Service\CustomValidator;

use Symfony\Component\Validator\Constraint;

/**
 * Class IsPasswordValid
 * @package App\Service\CustomValidator
 * @Annotation
 */
class IsPasswordValid extends Constraint
{
    public $message = 'Wrong current password!';

    /**
     * @return string
     */
    public function validatedBy()
    {
        return get_class($this).'Validator';
    }

}