<?php

namespace App\Service\Conversion\Event;

use App\Bus\EventBusInterface;
use App\Bus\EventHandlerInterface;
use App\Entity\Conversion;

readonly class SendFilesToConvertServiceEventHandler implements EventHandlerInterface
{
    public function __construct(
        private EventBusInterface $eventBus,
    ) {
    }

    public function __invoke(SendFilesToConvertServiceEvent $event): string
    {
        $conversion = new Conversion();

        return $conversion->getUuid();
    }
}