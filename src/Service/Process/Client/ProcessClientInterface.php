<?php

namespace App\Service\Process\Client;

use App\Entity\Process;

interface ProcessClientInterface
{
    public function requestProcessFiles(Process $process);
}