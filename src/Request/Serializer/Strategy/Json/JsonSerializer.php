<?php

namespace App\Request\Serializer\Strategy\Json;

use App\Request\Serializer\AbstractSerializer;
use Symfony\Component\Serializer\Encoder\JsonEncoder;
use Symfony\Component\Serializer\Mapping\Factory\ClassMetadataFactory;
use Symfony\Component\Serializer\Mapping\Loader\AttributeLoader;
use Symfony\Component\Serializer\NameConverter\CamelCaseToSnakeCaseNameConverter;
use Symfony\Component\Serializer\NameConverter\MetadataAwareNameConverter;
use Symfony\Component\Serializer\Normalizer\ObjectNormalizer;
use Symfony\Component\Serializer\Serializer;

class JsonSerializer extends AbstractSerializer implements JsonSerializerInterface
{
    protected string $format = 'json';

    public function __construct()
    {
        $classMetadataFactory = new ClassMetadataFactory(new AttributeLoader());

        $metadataAwareNameConverter = new MetadataAwareNameConverter($classMetadataFactory, new CamelCaseToSnakeCaseNameConverter());

        $normalizer = new ObjectNormalizer($classMetadataFactory, $metadataAwareNameConverter);

        $this->serializer = new Serializer([$normalizer], ['json' => new JsonEncoder()]);
    }
}