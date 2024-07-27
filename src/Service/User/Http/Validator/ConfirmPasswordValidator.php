<?php

namespace App\Service\User\Http\Validator;

use App\Service\Process\Http\Constraint\ValidProcessExtension;
use App\Service\User\Http\Constraint\ConfirmPassword;
use Exception;
use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\ConstraintValidator;

class ConfirmPasswordValidator extends ConstraintValidator
{
    /**
     * @throws Exception
     */
    public function validate(mixed $value, Constraint $constraint): void
    {
        if (!$constraint instanceof ConfirmPassword) {
            throw new Exception('Unexpected type of constraint (' . get_class($constraint) . ')');
        }

        if ($value !== $constraint->password) {
            $this->context->buildViolation($constraint->message)->atPath('password_confirm')->addViolation();
        }
    }
}