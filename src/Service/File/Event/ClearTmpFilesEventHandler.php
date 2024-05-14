<?php

namespace App\Service\File\Event;

use App\Bus\EventHandlerInterface;
use App\Service\File\Interface\FileManagerInterface;

readonly class ClearTmpFilesEventHandler implements EventHandlerInterface
{
    public function __construct(
        private FileManagerInterface $fileManager
    ) {
    }

    public function __invoke(ClearTmpFilesEvent $command): void
    {
        $tmpDir = $this->fileManager->getTempDirectoryPath();

        $this->fileManager->remove($tmpDir);
    }
}
