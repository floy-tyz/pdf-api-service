<?php

namespace App\Controller\Api;

use App\Bus\EventBusInterface;
use App\Service\Process\Map\ProcessMap;
use App\Traits\ResponseStatusTrait;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use OpenApi\Attributes as OA;

#[OA\Tag(name: 'Процесс')]
class ProcessMapApiController extends AbstractController
{
    use ResponseStatusTrait;

    public function __construct(
        private readonly EventBusInterface $eventBus
    ) {
    }

    #[Route('/api/v1/process/types', name: 'api.process.get.types', methods: ["GET"])]
    #[OA\Get(
        summary: 'Получение списка допустимых расширений и параметров для конвертации',
        responses: [
            new OA\Response(
                response: 200,
                description: 'Список',
                content: new OA\JsonContent(ref: '#/components/schemas/success-empty-response')
            ),
        ]
    )]
    public function getSupportedProcesses(): Response
    {
        return $this->success(ProcessMap::SUPPORTED_PROCESS_TYPES);
    }
}
