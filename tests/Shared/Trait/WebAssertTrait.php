<?php

declare(strict_types=1);

namespace App\Tests\Shared\Trait;

use App\Tests\Shared\Assert\IsUuid;
use PHPUnit\Framework\Constraint\Constraint;
use Symfony\Component\HttpFoundation\Response;

/**
 * @method static assertThat($value, Constraint $constraint, string $message = ''): void
 */
trait WebAssertTrait
{
    public function assertHttpResponseSuccess(Response $response, string $message = ''): void
    {
        self::assertContains($response->getStatusCode(), [Response::HTTP_OK, Response::HTTP_CREATED], $message);
    }

    public function assertHttpResponseBad(Response $response, string $message = ''): void
    {
        self::assertContains($response->getStatusCode(), [Response::HTTP_BAD_REQUEST, Response::HTTP_UNAUTHORIZED], $message);
    }

    public static function assertIsUuid($actual, string $message = ''): void
    {
        static::assertThat(
            $actual,
            new IsUuid($actual),
            $message,
        );
    }
}
