<?php

namespace App\Service\Conversion\Event;

use App\Bus\EventInterface;
use Symfony\Component\HttpFoundation\File\UploadedFile;

readonly class CreateNewConversionEvent implements EventInterface
{
    public function __construct(
        private string $convertExtension,
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

    public function getConvertExtension(): string
    {
        return $this->convertExtension;
    }
}