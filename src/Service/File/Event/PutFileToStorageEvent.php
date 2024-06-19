<?php

namespace App\Service\File\Event;

use App\Bus\EventInterface;

readonly class PutFileToStorageEvent implements EventInterface
{
    public function __construct(
        private string $bucket,
        private string $key,
        private string $filePath,
    ) {
    }

    public function getFilePath(): string
    {
        return $this->filePath;
    }

    public function getBucket(): string
    {
        return $this->bucket;
    }

    public function getKey(): string
    {
        return $this->key;
    }
}
