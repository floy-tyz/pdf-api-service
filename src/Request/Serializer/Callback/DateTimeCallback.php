<?php

namespace App\Request\Serializer\Callback;

use DateTime;

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