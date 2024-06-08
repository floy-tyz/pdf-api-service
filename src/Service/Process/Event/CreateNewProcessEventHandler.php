<?php

namespace App\Service\Process\Event;

use App\Bus\EventBusInterface;
use App\Bus\EventHandlerInterface;
use App\Entity\Process;
use App\Service\Process\Interface\ProcessRepositoryInterface;
use App\Service\File\Event\SaveFileEntityFromFilePathEvent;
use Symfony\Component\Messenger\Exception\ExceptionInterface;
use Symfony\Component\Messenger\MessageBusInterface;

readonly class CreateNewProcessEventHandler implements EventHandlerInterface
{
    public function __construct(
        private EventBusInterface $eventBus,
        private MessageBusInterface $messageBus,
        private ProcessRepositoryInterface $processRepository,
    ) {
    }

    /**
     * @throws ExceptionInterface
     */
    public function __invoke(CreateNewProcessEvent $event): string
    {
        $process = new Process();

        $process->setKey($event->getKey());
        $process->setContext($event->getContext());
        $process->setExtension($event->getExtension());

        foreach ($event->getFiles() as $file) {
            $process->addFile(
                $this->eventBus->publish(new SaveFileEntityFromFilePathEvent(
                    $file->getRealPath(),
                    $file->getClientOriginalName(),
                    $file->getClientOriginalExtension(),
                    true,
                )
            ));
        }

        $this->processRepository->save($process);

        $this->messageBus->dispatch(new SendFilesToProcessServiceEvent($process->getId()));

        return $process->getUuid();
    }
}