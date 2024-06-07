<?php

namespace App\Controller\Api;

use App\Bus\EventBusInterface;
use App\Entity\Conversion;
use App\Entity\File;
use App\Service\Conversion\Enum\ConversionStatusEnum;
use App\Service\File\Interface\FileRepositoryInterface;
use App\Traits\ResponseStatusTrait;
use Doctrine\ORM\EntityNotFoundException;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
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
    #[Route('/api/v1/conversion/{uuid}/files/converted', name: 'api.conversion.get.converted.files', methods: ["GET"])]
    public function getConvertedFiles(?Conversion $conversion): Response
    {
        if (!$conversion) {
            throw new EntityNotFoundException();
        }

        # todo replace with external validator
        if ($conversion->getStatus() !== ConversionStatusEnum::STATUS_CONVERTED->value) {
            return $this->failed();
        }

        /** @var array<File> $files */
        $files = $this->fileRepository->getConversionFiles($conversion->getId());

        // todo replace to serializer
        $tmp = [];
        foreach ($files as $file) {
            $href = explode('/', $file->getPath());
            unset($href[0]);
            $tmp[] = [
                'name' => $file->getOriginalFileName(),
                'href' => $this->generateUrl('api.files.get.by.uuid', ['uuid' => $file->getUuidFileName()]),
            ];
        }

        return $this->success(['files' => $tmp]);
    }

    /**
     * @throws EntityNotFoundException
     */
    #[Route('/api/v1/conversion/{uuid}/files/combined', name: 'api.conversion.get.combined.file', methods: ["GET"])]
    public function getCombinedFile(?Conversion $conversion): Response
    {
        if (!$conversion) {
            throw new EntityNotFoundException();
        }

        # todo replace with external validator
        if ($conversion->getStatus() !== ConversionStatusEnum::STATUS_CONVERTED->value) {
            return $this->failed();
        }

        $file = $this->fileRepository->getCombinedFile($conversion->getId());

        // todo replace to serializer
        return $this->success([
                'file' => [
                    'name' => $file->getOriginalFileName(),
                    'href' => $this->generateUrl('api.files.get.by.uuid', ['uuid' => $file->getUuidFileName()]),
                ]
            ]
        );
    }
}
