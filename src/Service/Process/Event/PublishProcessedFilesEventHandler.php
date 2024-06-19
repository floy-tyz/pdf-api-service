<?php

namespace App\Service\Process\Event;

use App\Bus\AsyncBusInterface;
use App\Bus\EventBusInterface;
use App\Bus\EventHandlerInterface;
use App\Entity\File;
use App\Entity\Process;
use App\Serializer\SerializerInterface;
use App\Service\File\Event\PutFileToStorageEvent;
use App\Service\File\Event\SaveFileEntityFromPathEvent;
use App\Service\File\Interface\FileRepositoryInterface;
use App\Service\Process\Event\External\ProcessFilesEvent;
use App\Service\Process\Interface\ProcessRepositoryInterface;
use Fresh\CentrifugoBundle\Service\CentrifugoInterface;
use Symfony\Component\Messenger\Exception\ExceptionInterface;
use Symfony\Component\Messenger\MessageBusInterface;

readonly class PublishProcessedFilesEventHandler implements EventHandlerInterface
{
    public function __construct(
        private CentrifugoInterface $centrifugo,
        private SerializerInterface $serializer,
    ) {
    }

    public function __invoke(PublishProcessedFilesEvent $event): void
    {
        $files = $this->serializer->normalize(
            $event->getFiles(),
            File::class,
            ['groups' => ['files']]
        );

        $this->centrifugo->publish(['files' => $files], $event->getProcessUuid());
    }
}