<?php

namespace App\Controller\Api;

use App\Bus\EventBusInterface;
use App\Exception\BusinessException;
use App\Service\Conversion\Event\CreateNewConversionEvent;
use App\Traits\ResponseStatusTrait;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class FileApiController extends AbstractController
{
    use ResponseStatusTrait;

    public function __construct(
        private readonly EventBusInterface $eventBus
    ) {
    }

    #[Route('/api/v1/upload/files', name: 'api.upload.files', methods: ["POST"])]
    public function uploadFile(Request $request): Response
    {
        $files = $request->files->all();

        if (count($files) < 1) {
            throw new BusinessException('Файлы не выбраны');
        }

        $convertExtension = $request->request->get('extension');

        if (!$convertExtension) {
            throw new BusinessException('Не выбран тип конвертации');
        }

        /** @var string $conversionUuid */
        $conversionUuid = $this->eventBus->publish(new CreateNewConversionEvent($convertExtension, $files));

        return $this->success(['uuid' => $conversionUuid]);
    }
}
