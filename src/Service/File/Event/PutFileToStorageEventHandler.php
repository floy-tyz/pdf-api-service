<?php

namespace App\Service\File\Event;

use App\Bus\EventHandlerInterface;
use App\Entity\File;
use App\Service\Aws\S3\S3Adapter;
use App\Service\Aws\S3\S3AdapterInterface;
use App\Service\File\Interface\FileManagerInterface;
use App\Service\File\Utils\Dir;

readonly class PutFileToStorageEventHandler implements EventHandlerInterface
{
    public function __construct(
        private S3AdapterInterface $s3Adapter
    ) {
    }

    public function __invoke(PutFileToStorageEvent $event): string
    {
        Dir::checkFileExist($event->getFilePath());

        return $this->s3Adapter->putObject(
            $event->getBucket(),
            $event->getKey(),
            $event->getFilePath(),
        );
    }
}
