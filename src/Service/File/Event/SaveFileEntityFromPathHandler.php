<?php

namespace App\Service\File\Event;

use App\Bus\EventHandlerInterface;
use App\Entity\File;
use App\Service\File\Interface\FileManagerInterface;
use App\Service\File\Utils\Dir;

readonly class SaveFileEntityFromPathHandler implements EventHandlerInterface
{
    public function __construct(
        private FileManagerInterface $fileManager,
    ) {
    }

    public function __invoke(SaveFileEntityFromPathEvent $event): File
    {
        Dir::checkFileExist($event->getFilePath());

        $file = new File();

        $file->setUsed(true);
        $file->setOriginalFileName($event->getOriginalFileName());
        $file->setExtension($event->getOriginalFileExtension());

        $this->fileManager->parseAndFillFileEntity($file, $event->getFilePath());

        return $file;
    }
}
