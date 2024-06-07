<?php

namespace App\Service\File\Interface;

use App\Entity\File;
use App\Repository\RepositoryInterface;

interface FileRepositoryInterface extends RepositoryInterface
{
    public function getUnusedFiles(): array;

    public function getConversionFiles(int $conversionId): array;

    public function getCombinedFile(int $conversionId): File;
}

