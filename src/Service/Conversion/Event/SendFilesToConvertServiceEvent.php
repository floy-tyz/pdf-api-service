<?php

namespace App\Service\Conversion\Event;

use App\Bus\EventInterface;
use App\Entity\Conversion;

readonly class SendFilesToConvertServiceEvent implements EventInterface
{
    public function __construct(private Conversion $conversion)
    {
    }

    public function getConversion(): Conversion
    {
        return $this->conversion;
    }
}