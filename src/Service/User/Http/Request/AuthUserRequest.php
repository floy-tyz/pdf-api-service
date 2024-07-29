<?php

namespace App\Service\User\Http\Request;

use App\Request\Http\AbstractRequestValidator;
use App\Service\User\Http\Dto\AuthUserRequestDto;

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