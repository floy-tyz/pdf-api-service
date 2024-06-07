<?php

namespace App\Service\Conversion\Event;

use App\Bus\EventInterface;
use Symfony\Component\HttpFoundation\File\UploadedFile;

readonly class SaveCombinedFileEvent implements EventInterface
{
    public function __construct(
        private string $conversionUuid,
        private UploadedFile $file
    ) {
    }

    public function getConversionUuid(): string
    {
        return $this->conversionUuid;
    }

    public function getFile(): UploadedFile
    {
        return $this->file;
    }
}