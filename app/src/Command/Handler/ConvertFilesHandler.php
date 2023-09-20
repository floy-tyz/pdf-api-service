<?php

namespace App\Command\Handler;

use App\Command\Message\ConvertFiles;
use Symfony\Component\Messenger\Attribute\AsMessageHandler;

#[AsMessageHandler]
class ConvertFilesHandler
{
    public function __invoke(ConvertFiles $convertFiles)
    {
        // TODO: Implement __invoke() method.
    }
}