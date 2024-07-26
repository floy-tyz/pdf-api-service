<?php

namespace App\Service\User\Event;

use App\Bus\EventInterface;
use Symfony\Component\HttpFoundation\File\UploadedFile;

readonly class MakeUserEvent implements EventInterface
{
    public function __construct(
        private string $login,
        private string $password,
        private ?array $roles = null,
    ) {
    }

    public function getPassword(): string
    {
        return $this->password;
    }

    public function getLogin(): string
    {
        return $this->login;
    }

    public function getRoles(): ?array
    {
        return $this->roles;
    }
}