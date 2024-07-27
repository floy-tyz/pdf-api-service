<?php
declare(strict_types=1);

namespace App\Serializer;

use ArrayObject;
use Symfony\Component\Serializer\Exception\BadMethodCallException;
use Symfony\Component\Serializer\Exception\ExceptionInterface;
use Symfony\Component\Serializer\Exception\ExtraAttributesException;
use Symfony\Component\Serializer\Exception\InvalidArgumentException;
use Symfony\Component\Serializer\Exception\LogicException;
use Symfony\Component\Serializer\Exception\RuntimeException;
use Symfony\Component\Serializer\Exception\UnexpectedValueException;

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
     *
     * @param mixed $object Object to normalize
     * @param string $class
     * @param array $context Context options for the normalizer
     *
     * @return array|string|int|float|bool|ArrayObject|null
     */
    public function normalize(mixed $object, string $class, array $context = []): array|string|int|float|bool|ArrayObject|null;

    /**
     * Denormalizes data back into an object of the given class.
     *
     * @param mixed $data Request to restore
     * @param string $type The expected class to instantiate
     * @param array $context Options available to the denormalizer
     *
     * @return mixed
     *
     * @throws BadMethodCallException   Occurs when the normalizer is not called in an expected context
     * @throws InvalidArgumentException Occurs when the arguments are not coherent or not supported
     * @throws UnexpectedValueException Occurs when the item cannot be hydrated with the given data
     * @throws ExtraAttributesException Occurs when the item doesn't have attribute to receive given data
     * @throws LogicException           Occurs when the normalizer is not supposed to denormalize
     * @throws RuntimeException         Occurs if the class cannot be instantiated
     * @throws ExceptionInterface       Occurs for all the other cases of errors
     */
    public function denormalize(mixed $data, string $type, array $context = []): mixed;
}