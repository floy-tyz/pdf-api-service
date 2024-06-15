<?php /** @noinspection PhpMultipleClassDeclarationsInspection */

namespace App\Serializer\Attribute;

use Attribute;
use Symfony\Component\Serializer\Annotation\Context;
use Symfony\Component\Serializer\Normalizer\AbstractNormalizer;

#[Attribute(Attribute::TARGET_PROPERTY|Attribute::IS_REPEATABLE)]
readonly class Callback
{
    /**
     * @param string $class
     * @param array $context
     */
    public function __construct(
        private string $class,
        private array $context = []
    ) {
    }

    public function getClass(): string
    {
        return $this->class;
    }

    public function getContext(): array
    {
        return $this->context;
    }
}