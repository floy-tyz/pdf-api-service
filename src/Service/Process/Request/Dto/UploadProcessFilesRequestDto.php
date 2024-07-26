<?php

namespace App\Service\Process\Request\Dto;

use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\Validator\Constraints as Assert;

class UploadProcessFilesRequestDto
{
    private ?string $clientIp = null;

    /**
     * @param string|null $key
     * @param string|null $extension
     * @param array<UploadedFile> $files
     * @param array<int, string> $context
     */
    public function __construct(
        #[Assert\NotNull(message: '"key" не должно быть null')]
        #[Assert\NotBlank(message: '"key" не должно быть пустым')]
        #[Assert\Type('string')]
        private readonly ?string $key = null,
        private readonly ?string $extension = null,
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