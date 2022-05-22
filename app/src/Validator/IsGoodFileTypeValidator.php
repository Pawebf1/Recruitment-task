<?php

namespace App\Validator;

use Symfony\Component\HttpFoundation\File\File;
use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\ConstraintValidator;
use Symfony\Component\Validator\Exception\UnexpectedTypeException;

class IsGoodFileTypeValidator extends ConstraintValidator
{
    public function validate(mixed $values, Constraint $constraint)
    {
        if (!$constraint instanceof IsGoodFileType) {
            throw new UnexpectedTypeException($constraint, IsGoodFileType::class);
        }

        if (null === $values || '' === $values) {
            return;
        }
        $mimeTypes = ["image/png", "image/jpeg", "application/msword", "application/vnd.openxmlformats-officedocument.wordprocessingml.document", "application/pdf"];
        foreach ($values as $value) {
            $file = new File($value->getPathName());
            if (!in_array($file->getMimeType(), $mimeTypes)) {
                $this->context->buildViolation($constraint->message)
                    ->setParameter('{{ string }}', $value->getClientOriginalName())
                    ->addViolation();
            }
        }
    }
}
