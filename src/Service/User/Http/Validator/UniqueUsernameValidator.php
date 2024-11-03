<?php

namespace App\Service\User\Http\Validator;

use App\Service\User\Http\Constraint\UniqueUsername;
use App\Service\User\Interface\UserRepositoryInterface;
use Exception;
use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\ConstraintValidator;

class UniqueUsernameValidator extends ConstraintValidator
{
    public function __construct(
        private readonly UserRepositoryInterface $userRepository
    ) {
    }

    /**
     * @throws Exception
     */
    public function validate(mixed $value, Constraint $constraint): void
    {
        if (!$constraint instanceof UniqueUsername) {
            throw new Exception('Unexpected type of constraint (' . get_class($constraint) . ')');
        }

        if ($this->userRepository->findOneBy(['login' => $value])) {
            $this->context->buildViolation($constraint->message)->addViolation();
        }
    }
}