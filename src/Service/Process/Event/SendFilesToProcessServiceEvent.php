<?php

namespace App\Service\Process\Event;

use App\Bus\EventInterface;

readonly class SendFilesToProcessServiceEvent implements EventInterface
{
    public function __construct(
        private int $processId
    ) {
    }

    public function getProcessId(): int
    {
        return $this->processId;
    }
}