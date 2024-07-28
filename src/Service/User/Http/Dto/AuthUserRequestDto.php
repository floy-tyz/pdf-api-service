<?php

namespace App\Service\User\Http\Dto;

use App\Service\User\Http\Constraint\UniqueUsername;
use Symfony\Component\Serializer\Attribute\SerializedName;
use Symfony\Component\Validator\Constraints as Assert;
use OpenApi\Attributes as OA;

readonly class AuthUserRequestDto
{
    public function __construct(
        #[Assert\Type('string')]
        #[Assert\NotNull]
        #[Assert\NotBlank]
        #[OA\Property(example: 'username')]
        private string $username,

        #[Assert\Type('string')]
        #[Assert\NotNull]
        #[Assert\NotBlank]
        #[OA\Property(example: 'password')]
        private string $password,
    ) {
    }

    public function getUsername(): string
    {
        return $this->username;
    }

    public function getPassword(): string
    {
        return $this->password;
    }
}