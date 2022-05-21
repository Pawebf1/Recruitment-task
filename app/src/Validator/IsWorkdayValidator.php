<?php

namespace App\Validator;

use Carbon\Carbon;
use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\ConstraintValidator;
use Symfony\Component\Validator\Exception\UnexpectedTypeException;

class IsWorkdayValidator extends ConstraintValidator
{

    public function validate(mixed $value, Constraint $constraint)
    {
        if (!$constraint instanceof IsWorkday) {
            throw new UnexpectedTypeException($constraint, IsWorkday::class);
        }

        if (null === $value || '' === $value) {
            return;
        }

        $date = new Carbon($value);

        if ($date->isWeekend()) {
            $this->context->buildViolation($constraint->message)
                ->setParameter('{{ string }}', $date->format('Y-m-d'))
                ->addViolation();
        }
    }
}