<?php

namespace App\Service\File\Interface;

use App\Repository\RepositoryInterface;

interface FileRepositoryInterface extends RepositoryInterface
{
    public function getUnusedFiles(): array;
}

