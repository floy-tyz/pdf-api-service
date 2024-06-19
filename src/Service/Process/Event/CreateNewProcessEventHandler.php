<?php

namespace App\Service\Process\Event;

use App\Bus\AsyncBusInterface;
use App\Bus\EventBusInterface;
use App\Bus\EventHandlerInterface;
use App\Entity\File;
use App\Entity\Process;
use App\Service\File\Event\PutFileToStorageEvent;
use App\Service\File\Event\SaveFileEntityFromPathEvent;
use App\Service\Process\Event\External\ProcessFilesEvent;
use App\Service\Process\Interface\ProcessRepositoryInterface;

readonly class CreateNewProcessEventHandler implements EventHandlerInterface
{
    const string S3_BUCKET = 'non-processed-files';

    public function __construct(
        private EventBusInterface $eventBus,
        private AsyncBusInterface $asyncBus,
        private ProcessRepositoryInterface $processRepository,
    ) {
    }

    /**
     */
    public function __invoke(CreateNewProcessEvent $event): string
    {
        $process = new Process();

        $process->setKey($event->getKey());
        $process->setContext($event->getContext());
        $process->setExtension($event->getExtension());

        $filesUuids = [];

        foreach ($event->getFiles() as $file) {

            /** @var File $fileEntity */
            $fileEntity = $this->eventBus->publish(new SaveFileEntityFromPathEvent(
                $file->getRealPath(),
                $file->getClientOriginalName(),
                $file->getClientOriginalExtension(),
            ));

            $this->eventBus->publish(new PutFileToStorageEvent(
                self::S3_BUCKET,
                $fileEntity->getUuid(),
                $file->getRealPath(),
            ));

            $process->addFile($fileEntity);

            $filesUuids[] = $fileEntity->getUuid();
        }

        $this->processRepository->save($process);

        $this->asyncBus->dispatch(new ProcessFilesEvent(
            $process->getUuid(),
            $filesUuids,
            $process->getKey(),
            $process->getExtension(),
            $process->getContext(),
        ));

        return $process->getUuid();
    }
}