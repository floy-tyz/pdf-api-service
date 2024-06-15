<?php

namespace App\Serializer;

use App\Serializer\Attribute\Callback;
use App\Serializer\Locator\CallbackLocator;
use ArrayObject;
use Exception;
use ReflectionProperty;
use Symfony\Component\PropertyInfo\Extractor\SerializerExtractor;
use Symfony\Component\Serializer\Exception\ExceptionInterface;
use Symfony\Component\Serializer\Mapping\Factory\ClassMetadataFactory;
use Symfony\Component\Serializer\Mapping\Loader\AttributeLoader;
use Symfony\Component\Serializer\Normalizer\AbstractNormalizer;
use Symfony\Component\Serializer\Normalizer\AbstractObjectNormalizer;
use Symfony\Component\Serializer\Serializer;
use Symfony\Contracts\Service\Attribute\Required;

abstract class AbstractSerializer
{
    private CallbackLocator $callbackLocator;

    /** @var Serializer  */
    protected Serializer $serializer;

    protected string $format;

    #[Required]
    public function setCallbackLocator(CallbackLocator $callbackLocator): void
    {
        $this->callbackLocator = $callbackLocator;
    }

    /**
     * @throws Exception
     */
    public function serialize(mixed $data, string $class, array $context = []): string
    {
        $this->configureContext($class, $context);

        return $this->serializer->serialize($data, $this->format, $context);
    }

    /**
     * @param mixed $object
     * @param string $class
     * @param array $context
     * @return array|string|int|float|bool|ArrayObject|null
     * @throws ExceptionInterface
     * @throws Exception
     */
    public function normalize(mixed $object, string $class, array $context = []): array|string|int|float|bool|null|ArrayObject
    {
        $this->configureContext($class, $context);

        return $this->serializer->normalize($object, $this->format, $context);
    }

    /**
     * @param mixed $data
     * @param string $type
     * @param array $context
     * @return mixed
     */
    public function denormalize(mixed $data, string $type, array $context = []): mixed
    {
        return $this->serializer->denormalize($data, $type, $this->format, $context);
    }

    /**
     * @param mixed $data
     * @param string $type
     * @param array $context
     * @return mixed
     */
    public function deserialize(mixed $data, string $type, array $context = []): mixed
    {
        return $this->serializer->deserialize($data, $type, $this->format, $context);
    }

    /**
     * @throws Exception
     */
    private function configureContext(string $class, array &$context): void
    {
        foreach ($this->getSerializedProperties($class, $context) as $property) {

            $property = new ReflectionProperty($class, $property);

            foreach ($property->getAttributes() as $attribute) {

                if ($attribute->getName() !== Callback::class) {
                    continue;
                }

                $arguments = $attribute->getArguments();

                if (isset($arguments['context']['groups']) && isset($context['groups'])) {

                    $intersect = array_intersect($arguments['context']['groups'], $context['groups']);

                    if (count($intersect) < 1) {
                        continue;
                    }
                }

                $context[AbstractNormalizer::CALLBACKS] = [
                    $property->getName() => $this->callbackLocator->get($arguments['class']),
                ];
            }
        }

        $context[AbstractObjectNormalizer::SKIP_UNINITIALIZED_VALUES] = false;
    }

    private function getSerializedProperties(string $class, array $context): array
    {
        $serializerClassMetadataFactory = new ClassMetadataFactory(new AttributeLoader());
        $serializerExtractor = new SerializerExtractor($serializerClassMetadataFactory);

        $groups = $context['groups'] ?? null;

        return $serializerExtractor->getProperties($class, ['serializer_groups' => $groups]);
    }
}