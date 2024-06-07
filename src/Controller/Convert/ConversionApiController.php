<?php

namespace App\Controller\Convert;

use App\Bus\EventBusInterface;
use App\Entity\Conversion;
use App\Service\Conversion\Enum\ConversionStatusEnum;
use App\Service\Conversion\Event\SaveCombinedFileEvent;
use App\Service\Conversion\Event\SaveConvertedFilesEvent;
use App\Service\File\Interface\FileRepositoryInterface;
use App\Traits\ResponseStatusTrait;
use Doctrine\ORM\EntityNotFoundException;
use Exception;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class ConversionApiController extends AbstractController
{
    use ResponseStatusTrait;

    public function __construct(
        private readonly EventBusInterface $eventBus,
        private readonly FileRepositoryInterface $fileRepository,
    ) {
    }

    /**
     * @throws EntityNotFoundException
     * @throws Exception
     */
    #[Route('/api/v1/conversion/{uuid}/files/converted', name: 'api.conversion.save.converted.files', methods: ["POST"])]
    public function saveConvertedFiles(Request $request, ?Conversion $conversion): Response
    {
        if (!$conversion) {
            throw new EntityNotFoundException();
        }

        if ($conversion->getStatus() === ConversionStatusEnum::STATUS_CONVERTED->value) {
            return $this->success();
        }

        $this->eventBus->publish(new SaveConvertedFilesEvent($conversion->getUuid(), $request->files->all()));

        return $this->success();
    }

    /**
     * @throws EntityNotFoundException
     * @throws Exception
     */
    #[Route('/api/v1/conversion/{uuid}/files/combined', name: 'api.conversion.save.combined.files', methods: ["POST"])]
    public function saveCombinedFiles(Request $request, ?Conversion $conversion): Response
    {
        if (!$conversion) {
            throw new EntityNotFoundException();
        }

        if ($conversion->getStatus() === ConversionStatusEnum::STATUS_CONVERTED->value) {
            return $this->success();
        }

        $files = $request->files->all();

        if (count($files) !== 1) {
            throw new Exception('Files should have exactly one uploaded file');
        }

        $this->eventBus->publish(new SaveCombinedFileEvent($conversion->getUuid(), array_pop($files)));

        return $this->success();
    }
}
