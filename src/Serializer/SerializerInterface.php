<?php
declare(strict_types=1);

namespace App\Serializer;

use ArrayObject;

interface SerializerInterface
{
    /**
     * Serializes data in the appropriate format.
     *
     * @param array<string, mixed> $context Options normalizers/encoders have access to
     */
    public function serialize(mixed $data, string $class, array $context = []): string;

    /**
     * Deserializes data into the given type.
     *
     * @template TObject of object
     * @template TType of string|class-string<TObject>
     *
     * @param mixed $data
     * @param string $type
     * @param array<string, mixed> $context
     *
     * @return mixed
     */
    public function deserialize(mixed $data, string $type, array $context = []): mixed;

    /**
     * Normalizes an object into a set of arrays/scalars.
     * @param mixed $object Object to normalize
     * @param string $class
     * @param array $context Context options for the normalizer
     * @return array|string|int|float|bool|ArrayObject|null
     */
    public function normalize(mixed $object, string $class, array $context = []): array|string|int|float|bool|ArrayObject|null;

    /**
     * Denormalizes data back into an object of the given class.
     * @param mixed $data Request to restore
     * @param string $type The expected class to instantiate
     * @param array $context Options available to the denormalizer
     * @return mixed
     */
    public function denormalize(mixed $data, string $type, array $context = []): mixed;
}