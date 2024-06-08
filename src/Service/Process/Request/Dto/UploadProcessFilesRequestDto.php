<?php

namespace App\Service\Process\Request\Dto;

use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\Validator\Constraints as Assert;

readonly class UploadProcessFilesRequestDto
{
    /**
     * @param string $key
     * @param string $extension
     * @param array<UploadedFile> $files
     * @param array<int, string> $context
     */
    public function __construct(
        #[Assert\NotNull]
        #[Assert\Type('string')]
        private string $key,
        private string $extension,
        private array $files,
        private array $context = [],
    ) {
    }

    public function getExtension(): string
    {
        return $this->extension;
    }

    public function getKey(): string
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
}