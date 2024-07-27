<?php

namespace App\Service\User\Http\Dto;

use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\Validator\Constraints as Assert;

readonly class RegisterUserRequestDto
{
    public function __construct(
        #[Assert\Type('string')]
        #[Assert\NotNull]
        #[Assert\NotBlank]
        private ?string $username = null,
        #[Assert\Type('string')]
        #[Assert\NotNull]
        #[Assert\NotBlank]
        private ?string $password = null,
        #[Assert\Type('string')]
        #[Assert\NotNull]
        #[Assert\NotBlank]
        private ?string $passwordConfirm = null,
    ) {
    }

    public function getUsername(): ?string
    {
        return $this->username;
    }

    public function getPassword(): ?string
    {
        return $this->password;
    }

    public function getPasswordConfirm(): ?string
    {
        return $this->passwordConfirm;
    }
}