<?php

namespace App\Service\Process\Http\Validator;

use App\Service\Process\Http\Constraint\ValidProcessExtension;
use Exception;
use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\ConstraintValidator;

class ValidProcessExtensionValidator extends ConstraintValidator
{
    /**
     * @throws Exception
     */
    public function validate(mixed $value, Constraint $constraint): void
    {
        if (!$constraint instanceof ValidProcessExtension) {
            throw new Exception('Unexpected type of constraint (' . get_class($constraint) . ')');
        }

        if ($value !== $constraint->allowedExtension) {
            $this->context->buildViolation($constraint->message)->atPath('extension')->addViolation();
        }
    }
}