<?php

namespace App\Validator;

use Symfony\Component\Validator\Constraint;

class IsWorkday extends Constraint
{
    public string $message = 'Selected day: "{{ string }}" is not a workday.';
}