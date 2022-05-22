<?php

namespace App\Validator;

use Symfony\Component\Validator\Constraint;

class IsGoodFileType extends Constraint
{
    public string $message = 'Selected file: "{{ string }}" is not in an accepted format.';
}
