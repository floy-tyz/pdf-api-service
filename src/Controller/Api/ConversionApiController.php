<?php

namespace App\Controller\Api;

use App\Bus\EventBusInterface;
use App\Entity\Conversion;
use App\Entity\File;
use App\Service\Conversion\Enum\ConversionStatusEnum;
use App\Service\Conversion\Event\SaveConvertedFilesEvent;
use App\Service\File\Interface\FileRepositoryInterface;
use App\Traits\ResponseStatusTrait;
use Doctrine\ORM\EntityNotFoundException;
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
     */
    #[Route('/api/v1/conversion/{uuid}/files/converted', name: 'api.conversion.save.converted.files', methods: ["POST"])]
    public function saveConversionFiles(Request $request, ?Conversion $conversion): Response
    {
        if (!$conversion) {
            throw new EntityNotFoundException();
        }

        if ($conversion->getStatus() === ConversionStatusEnum::STATUS_CONVERTED->value) {
            return $this->failed(['message' => 'Файлы уже сконвертированы']);
        }

        $this->eventBus->publish(new SaveConvertedFilesEvent($conversion->getUuid(), $request->files->all()));

        return $this->success();
    }

    /**
     * @throws EntityNotFoundException
     */
    #[Route('/api/v1/conversion/{uuid}/files/converted', name: 'api.conversion.get.converted.files', methods: ["GET"])]
    public function getConversionFiles(?Conversion $conversion): Response
    {
        if (!$conversion) {
            throw new EntityNotFoundException();
        }

        if ($conversion->getStatus() !== ConversionStatusEnum::STATUS_CONVERTED->value) {
            return $this->failed();
        }

        /** @var array<File> $files */
        $files = $this->fileRepository->getConversionActualFiles($conversion->getId());

        // todo replace to serializer
        $tmp = [];
        foreach ($files as $file) {
            $href = explode('/', $file->getPath());
            unset($href[0]);
            $tmp[] = [
                'name' => $file->getOriginalFileName(),
                'href' => DIRECTORY_SEPARATOR . implode('/', $href),
            ];
        }

        return $this->success(['files' => $tmp]);
    }
}
