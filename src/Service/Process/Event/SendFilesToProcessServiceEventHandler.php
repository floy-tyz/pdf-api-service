<?php

namespace App\Service\Process\Event;

use App\Bus\EventHandlerInterface;
use App\Entity\Process;
use App\Service\Process\Client\ProcessClientInterface;
use App\Service\Process\Enum\ProcessStatusEnum;
use App\Service\Process\Interface\ProcessRepositoryInterface;
use Symfony\Component\Messenger\Exception\UnrecoverableMessageHandlingException;

readonly class SendFilesToProcessServiceEventHandler implements EventHandlerInterface
{
    public function __construct(
        private ProcessRepositoryInterface $processRepository,
        private ProcessClientInterface $processClient,
    ) {
    }

    public function __invoke(SendFilesToProcessServiceEvent $event): void
    {
        $process = $this->processRepository->find($event->getProcessId());

        if (!$process instanceof Process) {
            throw new UnrecoverableMessageHandlingException();
        }

        $this->processClient->requestProcessFiles($process);

        $process->setStatus(ProcessStatusEnum::STATUS_SENT->value);

        $this->processRepository->save($process);
    }
}