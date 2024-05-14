<?php

namespace App\Service\Conversion\Event;

use App\Bus\EventInterface;

readonly class SendFilesToConvertServiceEvent implements EventInterface
{
    public function __construct(
        private int $conversionId
    ) {
    }


    public function getConversionId(): int
    {
        return $this->conversionId;
    }
}