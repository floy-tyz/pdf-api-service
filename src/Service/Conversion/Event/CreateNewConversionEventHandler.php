<?php

namespace App\Service\Conversion\Event;

use App\Bus\EventBusInterface;
use App\Bus\EventHandlerInterface;
use App\Entity\Conversion;
use App\Service\Conversion\Interface\ConversionRepositoryInterface;
use App\Service\File\Event\SaveFileEntityFromFilePathEvent;
use Symfony\Component\Messenger\MessageBusInterface;

readonly class CreateNewConversionEventHandler implements EventHandlerInterface
{
    public function __construct(
        private EventBusInterface $eventBus,
        private MessageBusInterface $messageBus,
        private ConversionRepositoryInterface $conversionRepository,
    ) {
    }

    public function __invoke(CreateNewConversionEvent $event): string
    {
        $conversion = new Conversion();

        $conversion->setExtension($event->getConvertExtension());

        foreach ($event->getFiles() as $file) {
            $conversion->addFile(
                $this->eventBus->publish(new SaveFileEntityFromFilePathEvent(
                    $file->getRealPath(),
                    $file->getClientOriginalName(),
                    $file->getClientOriginalExtension(),
                    true,
                )
            ));
        }

        $this->conversionRepository->save($conversion);

        $this->messageBus->dispatch(new SendFilesToConvertServiceEvent($conversion->getId()));

        return $conversion->getUuid();
    }
}