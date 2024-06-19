<?php

namespace App\Service\File\Event;

use App\Bus\EventInterface;

readonly class SaveFileEntityFromPathEvent implements EventInterface
{
    /**
     * @param string $filePath
     * @param string $originalFileName
     * @param string $originalFileExtension
     */
    public function __construct(
        private string $filePath,
        private string $originalFileName,
        private string $originalFileExtension
    ) {
    }

    public function getOriginalFileName(): string
    {
        return $this->originalFileName;
    }

    public function getOriginalFileExtension(): string
    {
        return $this->originalFileExtension;
    }

    public function getFilePath(): string
    {
        return $this->filePath;
    }
}
