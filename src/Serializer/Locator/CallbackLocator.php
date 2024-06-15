<?php

declare(strict_types=1);

namespace App\Serializer\Locator;

use App\Serializer\Callback\CallbackInterface;
use App\Serializer\Exception\CallbackException;
use Psr\Container\ContainerExceptionInterface;
use Symfony\Component\DependencyInjection\ServiceLocator;

readonly class CallbackLocator
{
    public function __construct(
        private ServiceLocator $locator
    ) {
    }

    /**
     * @throws CallbackException
     */
    public function get(string $class): CallbackInterface
    {
        try {
            return $this->locator->get($class);
        } catch (ContainerExceptionInterface) {
            throw new CallbackException("Mutator for class $class is not configured");
        }
    }
}
