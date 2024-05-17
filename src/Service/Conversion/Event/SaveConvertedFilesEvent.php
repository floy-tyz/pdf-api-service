<?php

namespace App\Service\Conversion\Event;

use App\Bus\EventInterface;
use Symfony\Component\HttpFoundation\File\UploadedFile;

readonly class SaveConvertedFilesEvent implements EventInterface
{
    public function __construct(
        private string $conversionUuid,
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

    public function getConversionUuid(): string
    {
        return $this->conversionUuid;
    }
}