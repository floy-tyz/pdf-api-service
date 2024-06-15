<?php

namespace App\Controller\Api;

use App\Bus\EventBusInterface;
use App\Service\Process\Map\ProcessMap;
use App\Traits\ResponseStatusTrait;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class ProcessMapApiController extends AbstractController
{
    use ResponseStatusTrait;

    public function __construct(
        private readonly EventBusInterface $eventBus
    ) {
    }

    #[Route('/api/v1/process/types', name: 'api.process.get.types', methods: ["GET"])]
    public function getSupportedProcesses(): Response
    {
        return $this->success(['data' => ProcessMap::SUPPORTED_PROCESS_TYPES]);
    }
}
