<?php

namespace App\Validator\Constraints;

use Symfony\Component\Validator\Constraint;

/**
 * @Annotation
 */
#[\Attribute] class ValidPaymentProcessor extends Constraint
{
    public $message = 'The payment processor "{{ value }}" is not valid.';
}


