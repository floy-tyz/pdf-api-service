<?php

declare(strict_types=1);

namespace App\Request\Serializer\Locator;

use App\Request\Serializer\Callback\CallbackInterface;
use App\Request\Serializer\Exception\CallbackException;
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
