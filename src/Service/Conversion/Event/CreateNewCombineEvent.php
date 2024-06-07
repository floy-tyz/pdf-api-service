<?php

namespace App\Service\Conversion\Event;

use App\Bus\EventInterface;
use Symfony\Component\HttpFoundation\File\UploadedFile;

readonly class CreateNewCombineEvent implements EventInterface
{
    public function __construct(
        private string $extension,
        private array $files
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
}