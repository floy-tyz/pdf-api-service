<?php

namespace App\Service\Process\Http\Dto;

use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\Validator\Constraints as Assert;

class UploadProcessFilesRequestDto
{
    private ?string $clientIp = null;

    /**
     * @param array<UploadedFile> $files
     * @param array<int, string> $context
     */
    public function __construct(
        #[Assert\Type('string')]
        #[Assert\NotNull]
        #[Assert\NotBlank]
        private readonly ?string $key = null,
        #[Assert\Type('string')]
        #[Assert\NotNull]
        #[Assert\NotBlank]
        private readonly ?string $extension = null,
        #[Assert\Count(min: 1, minMessage: 'Файлы не указаны')]
        private readonly array $files = [],
        private readonly array $context = [],
    ) {
    }

    public function getExtension(): ?string
    {
        return $this->extension;
    }

    public function getKey(): ?string
    {
        return $this->key;
    }

    public function getFiles(): array
    {
        return $this->files;
    }

    public function getContext(): array
    {
        return $this->context;
    }

    public function getClientIp(): ?string
    {
        return $this->clientIp;
    }

    public function setClientIp(?string $clientIp): void
    {
        $this->clientIp = $clientIp;
    }
}