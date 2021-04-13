<?php

namespace App\Application\Validator;

use Symfony\Component\Validator\Constraint;

/**
 * @Annotation
 */
class UniqueEmail extends Constraint
{
    public string $message = "Cet E-mail existe déjà.";
}