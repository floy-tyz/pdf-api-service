<?php

namespace App\Serializer\Callback;

use DateTime;
use Exception;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;

readonly class UrlCallback implements CallbackInterface
{
    public function __construct(
        private UrlGeneratorInterface $router
    ) {
    }

    /**
     * @throws Exception
     */
    public function __invoke(
        mixed $innerObject,
        mixed $outerObject,
        string $attributeName,
        string $format = null,
        array $context = []
    ): array|string|int|float
    {
        if (empty($context['url']['route']) || empty($context['url']['parameters'])) {
            throw new Exception("Route or parameters missing");
        }

        $parameters = [];

        foreach ($context['url']['parameters'] as $key => $getter) {
            $parameters[$key] = $outerObject->$getter();
        }

        return $this->router->generate($context['url']['route'], $parameters);
    }
}