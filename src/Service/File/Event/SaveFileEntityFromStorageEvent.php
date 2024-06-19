<?php

namespace App\Service\File\Event;

use App\Bus\EventInterface;

readonly class SaveFileEntityFromStorageEvent implements EventInterface
{
    /**
     * @param string $key
     * @param string $bucketName
     * @param string $fileName
     * @param string $extension
     */
    public function __construct(
        private string $key,
        private string $bucketName,
        private string $fileName,
        private string $extension
    ) {
    }

    public function getFileName(): string
    {
        return $this->fileName;
    }

    public function getExtension(): string
    {
        return $this->extension;
    }

    public function getKey(): string
    {
        return $this->key;
    }

    public function getBucketName(): string
    {
        return $this->bucketName;
    }
}
