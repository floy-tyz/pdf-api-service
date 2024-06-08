<?php

namespace App\Service\Process\Event;

use App\Bus\EventBusInterface;
use App\Bus\EventHandlerInterface;
use App\Entity\Process;
use App\Service\Process\Enum\ProcessStatusEnum;
use App\Service\Process\Interface\ProcessRepositoryInterface;
use App\Service\File\Event\SaveFileEntityFromFilePathEvent;
use DateTime;

readonly class SaveProcessedFilesEventHandler implements EventHandlerInterface
{
    public function __construct(
        private EventBusInterface $eventBus,
        private ProcessRepositoryInterface $processRepository,
    ) {
    }

    public function __invoke(SaveProcessedFilesEvent $event): void
    {
        $process = $event->getProcess();

        $userFilesUuidMap = [];

        foreach ($process->getFiles() as $file) {
            $userFilesUuidMap[$file->getUuidFileName()] = pathinfo($file->getOriginalFileName(), PATHINFO_FILENAME);
            $file->setIsUsed(false);
        }

        foreach ($event->getFiles() as $file) {
            $originalFileName = pathinfo($file->getClientOriginalName(), PATHINFO_FILENAME);
            if (in_array(pathinfo($file->getClientOriginalName(), PATHINFO_FILENAME), $userFilesUuidMap)) {
                $originalFileName = $userFilesUuidMap[pathinfo($file->getClientOriginalName(), PATHINFO_FILENAME)];
            }
            $process->addFile(
                $this->eventBus->publish(new SaveFileEntityFromFilePathEvent(
                    $file->getRealPath(),
                    $originalFileName . '.' . $file->getClientOriginalExtension(),
                    $file->getClientOriginalExtension(),
                    true,
                )
            ));
        }

        $process->setStatus(ProcessStatusEnum::STATUS_PROCESSED->value);
        $process->setDateProcessed(new DateTime());

        $this->processRepository->save($process);
    }
}