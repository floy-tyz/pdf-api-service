<?php

namespace App\Controller\Api;

use App\Bus\EventBusInterface;
use App\Service\Conversion\Map\ConversionMap;
use App\Traits\ResponseStatusTrait;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class ConversionMapApiController extends AbstractController
{
    use ResponseStatusTrait;

    public function __construct(
        private readonly EventBusInterface $eventBus
    ) {
    }

    #[Route('/api/v1/conversion/types', name: 'api.conversion.get.types', methods: ["GET"])]
    public function getSupportedConversionTypes(): Response
    {
        return $this->success([
            'types' => [
                'convert' => array_keys(ConversionMap::SUPPORTED_TYPE_TO_TYPE_CONVERTS),
                'combine' => array_keys(ConversionMap::SUPPORTED_TYPE_TO_TYPE_CONVERTS)
            ]
        ]);
    }
}
