<?php

namespace App\Service\Process\Event;

use App\Bus\EventHandlerInterface;
use App\Entity\File;
use App\Request\Serializer\SerializerInterface;
use Fresh\CentrifugoBundle\Service\CentrifugoInterface;

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