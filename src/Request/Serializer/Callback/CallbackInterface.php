<?php

namespace App\Request\Serializer\Callback;

interface CallbackInterface
{
    /**
     * @param mixed $innerObject
     * @param mixed $outerObject
     * @param string $attributeName
     * @param string|null $format
     * @param array $context
     * @return array|string|int|float
     */
    public function __invoke(
        mixed $innerObject,
        mixed $outerObject,
        string $attributeName,
        string $format = null,
        array $context = []
    ): array|string|int|float;
}