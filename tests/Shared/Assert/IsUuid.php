<?php

namespace App\Tests\Shared\Assert;

use PHPUnit\Framework\Constraint\Constraint;
use Symfony\Component\Uid\Uuid;

final class IsUuid extends Constraint
{
    public function __construct(
        private readonly string $value
    ) {
    }

    protected function matches($other): bool
    {
        return Uuid::isValid($other);
    }

    public function toString(): string
    {
        return sprintf(
            'value "%s"',
            $this->value,
        );
    }
}
