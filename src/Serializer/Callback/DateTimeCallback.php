<?php

namespace App\Serializer\Callback;

use DateTime;
use DateTimeInterface;

readonly class DateTimeCallback implements CallbackInterface
{
    public function __invoke(
        mixed $innerObject,
        mixed $outerObject,
        string $attributeName,
        string $format = null,
        array $context = []
    ): array|string|int|float
    {
        return $innerObject instanceof DateTime ? $innerObject->format('d-m-Y H:i:s') : 'Неправильное время';
    }
}