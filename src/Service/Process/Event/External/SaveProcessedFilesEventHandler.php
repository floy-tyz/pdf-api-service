<?php

namespace App\Service\Process\Event\External;

use App\Bus\AsyncHandlerInterface;
use App\Bus\EventBusInterface;
use App\Entity\File;
use App\Entity\Process;
use App\Service\File\Event\SaveFileEntityFromStorageEvent;
use App\Service\Process\Enum\ProcessStatusEnum;
use App\Service\Process\Event\PublishProcessedFilesEvent;
use App\Service\Process\Interface\ProcessRepositoryInterface;
use DateTime;
use Exception;
use Symfony\Component\Uid\Uuid;

readonly class SaveProcessedFilesEventHandler implements AsyncHandlerInterface
{
    const string S3_BUCKET = 'processed-files';

    public function __construct(
        private EventBusInterface $eventBus,
        private ProcessRepositoryInterface $processRepository,
    ) {
    }

    /**
     * @throws Exception
     */
    public function __invoke(SaveProcessedFilesEvent $event): void
    {
        $process = $this->processRepository->findOneBy(['uuid' => $event->getProcessUuid()]);

        if (!$process instanceof Process) {
            throw new Exception('Process with uuid ' . $event->getProcessUuid() . ' not found');
        }

        $userFilesUuidMap = [];

        foreach ($process->getFiles() as $file) {
            $userFilesUuidMap[$file->getUuid()] = pathinfo($file->getOriginalFileName(), PATHINFO_FILENAME);
            $file->setIsUsed(false);
        }

        $processedFilesEntities = [];

        foreach ($event->getFilesKeys() as $originalUuid => $processedUuid) {

            $processedFileName = $originalUuid;

            if (array_key_exists($originalUuid, $userFilesUuidMap)) {
                $processedFileName = $userFilesUuidMap[$originalUuid];
            }

            /** @var File $processedFileEntity */
            $processedFileEntity = $this->eventBus->publish(new SaveFileEntityFromStorageEvent(
                $processedUuid,
                self::S3_BUCKET,
                $processedFileName . '.' . $event->getExtension(),
                $event->getExtension(),
            ));

            $processedFileEntity->setUuid($processedUuid);

            $process->addFile($processedFileEntity);

            $processedFilesEntities[] = $processedFileEntity;
        }

        $process->setStatus(ProcessStatusEnum::STATUS_PROCESSED->value);
        $process->setDateProcessed(new DateTime());

        $this->processRepository->save($process);

        $this->eventBus->publish(new PublishProcessedFilesEvent(
            $process->getUuid(),
            $processedFilesEntities,
        ));
    }
}