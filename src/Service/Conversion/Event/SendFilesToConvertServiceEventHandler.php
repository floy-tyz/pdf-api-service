<?php

namespace App\Service\Conversion\Event;

use App\Bus\EventBusInterface;
use App\Bus\EventHandlerInterface;
use App\Entity\Conversion;
use App\Service\Conversion\Client\ConversionClientInterface;
use App\Service\Conversion\Interface\ConversionRepositoryInterface;
use Symfony\Component\Messenger\Exception\UnrecoverableMessageHandlingException;

readonly class SendFilesToConvertServiceEventHandler implements EventHandlerInterface
{
    public function __construct(
        private ConversionRepositoryInterface $conversionRepository,
        private ConversionClientInterface $conversionClient,
    ) {
    }

    public function __invoke(SendFilesToConvertServiceEvent $event): void
    {
        $conversion = $this->conversionRepository->find($event->getConversionId());

        if (!$conversion instanceof Conversion) {
            throw new UnrecoverableMessageHandlingException();
        }

        $this->conversionClient->requestConvertFiles(
            $conversion->getUuid(),
            $conversion->getExtension(),
            $conversion->getFiles()->toArray()
        );
    }
}