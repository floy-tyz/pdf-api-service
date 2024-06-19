<?php

namespace App\Service\File\Event;

use App\Bus\EventHandlerInterface;
use App\Entity\File;
use App\Service\Aws\S3\S3AdapterInterface;
use Exception;

readonly class SaveFileEntityFromStorageHandler implements EventHandlerInterface
{
    public function __construct(
        private S3AdapterInterface $s3Adapter
    ) {
    }

    /**
     * @throws Exception
     */
    public function __invoke(SaveFileEntityFromStorageEvent $event): File
    {
        $headData = $this->s3Adapter->getObjectHead($event->getBucketName(), $event->getKey());

        $file = new File();

        $file->setUsed(true);
        $file->setOriginalFileName($event->getFileName());
        $file->setExtension($event->getExtension());
        $file->setMimeType($headData['mime_type']);
        $file->setSize($headData['size']);

        return $file;
    }
}
