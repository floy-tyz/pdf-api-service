<?php

namespace App\Service\User\Http\Request;

use App\Security\Request\AbstractRequestValidator;
use App\Service\User\Http\Constraint\ConfirmPassword;
use App\Service\User\Http\Dto\RegisterUserRequestDto;

class RegisterUserRequest extends AbstractRequestValidator
{
    public function getDto(): RegisterUserRequestDto
    {
        $request = $this->request->getMainRequest();

        /** @var RegisterUserRequestDto $dto */
        $dto = $this->deserializeRequest($request, RegisterUserRequestDto::class);

        $this->validate($dto);
        $this->validateConfirmPassword($dto);

        return $dto;
    }

    private function validateConfirmPassword(RegisterUserRequestDto $dto): void
    {
        $constraints = [
            new ConfirmPassword($dto->getPassword()),
        ];

        $this->validate($dto->getPasswordConfirm(), $constraints);
    }
}