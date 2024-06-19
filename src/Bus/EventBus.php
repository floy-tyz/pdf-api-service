<?php

declare(strict_types=1);

namespace App\Bus;

use Symfony\Component\Messenger\HandleTrait;
use Symfony\Component\Messenger\MessageBusInterface;

final class EventBus implements EventBusInterface
{
    use HandleTrait;

    private MessageBusInterface $messageBus;

    public function __construct(
        MessageBusInterface $eventBus
    ) {
        $this->messageBus = $eventBus;
    }

    public function publish(EventInterface $event): mixed
    {
        return $this->handle($event);
    }
}
