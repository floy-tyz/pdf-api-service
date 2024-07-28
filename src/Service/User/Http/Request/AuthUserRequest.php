<?php

namespace App\Service\User\Http\Request;

use App\Security\Request\AbstractRequestValidator;
use App\Service\User\Http\Constraint\ConfirmPassword;
use App\Service\User\Http\Dto\AuthUserRequestDto;
use App\Service\User\Http\Dto\RegisterUserRequestDto;

class AuthUserRequest extends AbstractRequestValidator
{
    public function getDto(): AuthUserRequestDto
    {
        $request = $this->request->getMainRequest();

        /** @var AuthUserRequestDto $dto */
        $dto = $this->deserializeRequest($request, AuthUserRequestDto::class);

        $this->validate($dto);

        return $dto;
    }
}