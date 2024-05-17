<?php

namespace App\Service\Conversion\Event;

use App\Bus\EventBusInterface;
use App\Bus\EventHandlerInterface;
use App\Entity\Conversion;
use App\Service\Conversion\Enum\ConversionStatusEnum;
use App\Service\Conversion\Interface\ConversionRepositoryInterface;
use App\Service\File\Event\SaveFileEntityFromFilePathEvent;
use DateTime;

readonly class SaveConvertedFilesEventHandler implements EventHandlerInterface
{
    public function __construct(
        private EventBusInterface $eventBus,
        private ConversionRepositoryInterface $conversionRepository,
    ) {
    }

    public function __invoke(SaveConvertedFilesEvent $event): void
    {
        /** @var Conversion $conversion */
        $conversion = $this->conversionRepository->findOneBy(['uuid' => $event->getConversionUuid()]);

        $userFilesUuidMap = [];

        foreach ($conversion->getFiles() as $file) {
            $userFilesUuidMap[$file->getUuidFileName()] = pathinfo($file->getOriginalFileName(), PATHINFO_FILENAME);
            $file->setIsUsed(false);
        }

        foreach ($event->getFiles() as $file) {
            $originalFileName = $userFilesUuidMap[pathinfo($file->getClientOriginalName(), PATHINFO_FILENAME)];
            $conversion->addFile(
                $this->eventBus->publish(new SaveFileEntityFromFilePathEvent(
                    $file->getRealPath(),
                    $originalFileName . '.' . $file->getClientOriginalExtension(),
                    $file->getClientOriginalExtension(),
                    true,
                )
            ));
        }

        $conversion->setStatus(ConversionStatusEnum::STATUS_CONVERTED->value);
        $conversion->setDateConverted(new DateTime());

        $this->conversionRepository->save($conversion);
    }
}