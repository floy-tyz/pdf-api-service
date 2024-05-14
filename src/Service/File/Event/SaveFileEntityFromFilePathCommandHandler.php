<?php

namespace App\Service\File\Event;

use App\Entity\File;
use App\Service\File\Interface\FileManagerInterface;
use App\Service\File\Utils\Dir;
use App\Bus\EventHandlerInterface;

readonly class SaveFileEntityFromFilePathCommandHandler implements EventHandlerInterface
{
    public function __construct(
        private FileManagerInterface $fileManager,
    ) {
    }

    public function __invoke(SaveFileEntityFromFilePathEvent $command): File
    {
        $absoluteFilePath = $command->isAbsolutePath()
            ? $command->getFilePath()
            : $this->fileManager->getAbsolutePath($command->getFilePath());

        $relativeFilePath = $command->getFilePath();

        Dir::checkFileExist($absoluteFilePath);

        $file = new File();

        if ($command->isMoveFileToStorage()) {
            $relativeFilePath = $this->fileManager->moveFileToStorage(
                $file->getUuidFileName(),
                $absoluteFilePath,
                $command->getFileName(),
                $command->getExtension()
            );
            $absoluteFilePath = $this->fileManager->getAbsolutePath($relativeFilePath);
        }

        $this->fileManager->parseAndFillFileEntity($file, $relativeFilePath, $absoluteFilePath);

        $file->setOriginalFileName($command->getFileName());

        $file->setUsed(true);

        return $file;
    }
}
