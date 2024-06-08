<?php

namespace App\Service\File\Interface;

use App\Entity\File;
use App\Repository\RepositoryInterface;

interface FileRepositoryInterface extends RepositoryInterface
{
    public function getUnusedFiles(): array;

    /**
     * @param int $process
     * @return array<File>
     */
    public function getProcessFiles(int $process): array;
}

