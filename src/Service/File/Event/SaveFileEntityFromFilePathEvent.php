<?php

namespace App\Service\File\Event;

use App\Bus\EventInterface;

class SaveFileEntityFromFilePathEvent implements EventInterface
{
    public function __construct(
        private string $filePath,
        private string $fileName,
        private ?string $extension = null,
        private bool $isAbsolutePath = false,
        private bool $moveFileToStorage = true
    ) {
    }

    public function getFilePath(): string
    {
        return $this->filePath;
    }

    public function setFilePath(string $filePath): void
    {
        $this->filePath = $filePath;
    }

    public function getFileName(): string
    {
        return $this->fileName;
    }

    public function setFileName(string $fileName): void
    {
        $this->fileName = $fileName;
    }

    public function isMoveFileToStorage(): bool
    {
        return $this->moveFileToStorage;
    }

    public function setMoveFileToStorage(bool $moveFileToStorage): void
    {
        $this->moveFileToStorage = $moveFileToStorage;
    }

    public function isAbsolutePath(): bool
    {
        return $this->isAbsolutePath;
    }

    public function setIsAbsolutePath(bool $isAbsolutePath): void
    {
        $this->isAbsolutePath = $isAbsolutePath;
    }

    public function getExtension(): ?string
    {
        return $this->extension;
    }

    public function setExtension(?string $extension): void
    {
        $this->extension = $extension;
    }
}
