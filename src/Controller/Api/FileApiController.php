<?php

namespace App\Controller\Api;

use App\Bus\EventBusInterface;
use App\Entity\File;
use App\Service\Aws\S3\S3AdapterInterface;
use App\Service\File\Interface\FileManagerInterface;
use App\Service\File\Interface\FileRepositoryInterface;
use App\Traits\ResponseStatusTrait;
use Doctrine\ORM\EntityNotFoundException;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\DependencyInjection\ParameterBag\ParameterBagInterface;
use Symfony\Component\HttpFoundation\BinaryFileResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\ResponseHeaderBag;
use Symfony\Component\Routing\Attribute\Route;

class FileApiController extends AbstractController
{
    use ResponseStatusTrait;

    public function __construct(
        private readonly EventBusInterface $eventBus,
        private readonly FileRepositoryInterface $fileRepository,
        private readonly ParameterBagInterface $parameterBag,
        private readonly S3AdapterInterface $s3Adapter,
        private readonly FileManagerInterface $fileManager
    ) {
    }

    /**
     * @throws EntityNotFoundException
     */
    #[Route('/api/v1/files/{uuid}', name: 'api.files.get.by.uuid', methods: ["GET"])]
    public function getFile(Request $request): Response
    {
        /** @var File $file */
        $file = $this->fileRepository->findOneBy(['uuid' => $request->get('uuid')]);

        if (!$file) {
            throw new EntityNotFoundException();
        }

        $filePath = $this->fileManager->getTempFilePath();

        file_put_contents($filePath, $this->s3Adapter->getObjectContent('processed-files', $file->getUuid())) ;

        BinaryFileResponse::trustXSendfileTypeHeader();

        return $this->file($filePath, $file->getOriginalFileName(), ResponseHeaderBag::DISPOSITION_INLINE);
    }
}
