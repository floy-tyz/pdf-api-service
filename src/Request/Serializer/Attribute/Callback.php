<?php /** @noinspection PhpMultipleClassDeclarationsInspection */

namespace App\Request\Serializer\Attribute;

use Attribute;

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