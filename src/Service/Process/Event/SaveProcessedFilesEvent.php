<?php

namespace App\Service\Process\Event;

use App\Bus\EventInterface;
use App\Entity\Process;
use Symfony\Component\HttpFoundation\File\UploadedFile;

readonly class SaveProcessedFilesEvent implements EventInterface
{
    public function __construct(
        private Process $process,
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
    public function getProcess(): Process
    {
        return $this->process;
    }
}