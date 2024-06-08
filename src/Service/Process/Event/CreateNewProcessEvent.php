<?php

namespace App\Service\Process\Event;

use App\Bus\EventInterface;
use Symfony\Component\HttpFoundation\File\UploadedFile;

readonly class CreateNewProcessEvent implements EventInterface
{
    public function __construct(
        private string $key,
        private string $extension,
        private array $files,
        private array $context
    ) {
    }

    /**
     * @return array<UploadedFile>
     */
    public function getFiles(): array
    {
        return $this->files;
    }

    public function getExtension(): string
    {
        return $this->extension;
    }

    public function getContext(): array
    {
        return $this->context;
    }

    public function getKey(): string
    {
        return $this->key;
    }
}