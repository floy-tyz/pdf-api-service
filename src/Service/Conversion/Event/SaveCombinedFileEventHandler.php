<?php

namespace App\Service\Conversion\Event;

use App\Bus\EventBusInterface;
use App\Bus\EventHandlerInterface;
use App\Entity\Conversion;
use App\Service\Conversion\Enum\ConversionStatusEnum;
use App\Service\Conversion\Interface\ConversionRepositoryInterface;
use App\Service\File\Event\SaveFileEntityFromFilePathEvent;
use DateTime;

readonly class SaveCombinedFileEventHandler implements EventHandlerInterface
{
    public function __construct(
        private EventBusInterface $eventBus,
        private ConversionRepositoryInterface $conversionRepository,
    ) {
    }

    public function __invoke(SaveCombinedFileEvent $event): void
    {
        /** @var Conversion $conversion */
        $conversion = $this->conversionRepository->findOneBy(['uuid' => $event->getConversionUuid()]);

        $file = $event->getFile();

        $conversion->addFile(
            $this->eventBus->publish(new SaveFileEntityFromFilePathEvent(
                $file->getRealPath(),
                'converted_easypdf' . '.' . $file->getClientOriginalExtension(),
                $file->getClientOriginalExtension(),
                true,
            )
        ));

        $conversion->setStatus(ConversionStatusEnum::STATUS_CONVERTED->value);
        $conversion->setDateConverted(new DateTime());

        $this->conversionRepository->save($conversion);
    }
}