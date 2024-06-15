<?php

namespace App\Controller\Api;

use App\Bus\EventBusInterface;
use App\Entity\File;
use App\Service\File\Interface\FileRepositoryInterface;
use App\Service\File\Utils\Dir;
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
        private readonly ParameterBagInterface $parameterBag
    ) {
    }

    /**
     * @throws EntityNotFoundException
     */
    #[Route('/api/v1/files/{uuid}', name: 'api.files.get.by.uuid', methods: ["GET"])]
    public function getFile(Request $request): Response
    {
        /** @var File $file */
        $file = $this->fileRepository->findOneBy(['uuidFileName' => $request->get('uuid')]);

        if (!$file) {
            throw new EntityNotFoundException();
        }

        $filePath = $this->parameterBag->get('kernel.project_dir') . DIRECTORY_SEPARATOR . $file->getPath();

        Dir::checkFileExist($filePath);

        BinaryFileResponse::trustXSendfileTypeHeader();

        $response = new BinaryFileResponse($filePath);

        $response->setContentDisposition(
            ResponseHeaderBag::DISPOSITION_ATTACHMENT,
            $file->getOriginalFileName()
        );

        return $response;
    }
}
