<?php

namespace App\Service\Process\Event;

use App\Bus\EventInterface;
use App\Entity\File;
use Symfony\Component\HttpFoundation\File\UploadedFile;

readonly class PublishProcessedFilesEvent implements EventInterface
{
    public function __construct(
        private string $processUuid,
        private array $files,
    ) {
    }

    /**
     * @return array<File>
     */
    public function getFiles(): array
    {
        return $this->files;
    }

    public function getProcessUuid(): string
    {
        return $this->processUuid;
    }
}