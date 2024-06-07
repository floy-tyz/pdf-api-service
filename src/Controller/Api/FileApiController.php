<?php

namespace App\Controller\Api;

use App\Bus\EventBusInterface;
use App\Entity\File;
use App\Exception\BusinessException;
use App\Service\Conversion\Event\CreateNewCombineEvent;
use App\Service\Conversion\Event\CreateNewConvertEvent;
use App\Service\Conversion\Map\ConversionMap;
use App\Service\File\Interface\FileRepositoryInterface;
use App\Service\File\Utils\Dir;
use App\Traits\ResponseStatusTrait;
use Doctrine\ORM\EntityNotFoundException;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\DependencyInjection\ParameterBag\ParameterBagInterface;
use Symfony\Component\HttpFoundation\BinaryFileResponse;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\ResponseHeaderBag;
use Symfony\Component\Routing\Annotation\Route;

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

    #[Route('/api/v1/convert/files', name: 'api.conversion.upload.convert.files', methods: ["POST"])]
    public function uploadConvertFiles(Request $request): Response
    {
        $files = $request->files->all();

        # todo request external validator
        if (count($files) < 1) {
            throw new BusinessException('Файлы не выбраны');
        }
        $extension = $request->request->get('extension');
        if (!$extension) {
            throw new BusinessException('Не выбран тип конвертации');
        }
        if (!array_key_exists($extension, ConversionMap::SUPPORTED_TYPE_TO_TYPE_CONVERTS)) {
            throw new BusinessException('Не поддерживаемый тип конвертации');
        }
        $supportedConvertExtensions = ConversionMap::SUPPORTED_TYPE_TO_TYPE_CONVERTS[$extension];
        foreach ($files as $file) {
            if ($file->getError()){
                throw new BusinessException($file->getErrorMessage());
            }
            if (!in_array($file->getClientOriginalExtension(), $supportedConvertExtensions)) {
                throw new BusinessException('Файл "' . $file->getClientOriginalName()
                    . '" имеет не поддерживаемое расширение "' . $file->getClientOriginalExtension() . '"');
            }
        }

        /** @var string $conversionUuid */
        $conversionUuid = $this->eventBus->publish(new CreateNewConvertEvent($extension, $files));

        return $this->success(['uuid' => $conversionUuid]);
    }

    #[Route('/api/v1/combine/files', name: 'api.conversion.upload.combine.files', methods: ["POST"])]
    public function uploadCombineFiles(Request $request): Response
    {
        /** @var array<UploadedFile> $files */
        $files = $request->files->all();

        # todo request external validator
        if (count($files) < 1) {
            throw new BusinessException('Файлы не выбраны');
        }
        $extension = $request->request->get('extension');
        if (!$extension) {
            throw new BusinessException('Не выбран тип конвертации');
        }
        if (!array_key_exists($extension, ConversionMap::SUPPORTED_TYPES_TO_TYPE_COMBINES)) {
            throw new BusinessException('Не поддерживаемый тип конвертации');
        }
        $supportedCombineExtensions = ConversionMap::SUPPORTED_TYPES_TO_TYPE_COMBINES[$extension];
        foreach ($files as $file) {
            if ($file->getError()){
                throw new BusinessException($file->getErrorMessage());
            }
            if (!in_array($file->getClientOriginalExtension(), $supportedCombineExtensions)) {
                throw new BusinessException('Файл "' . $file->getClientOriginalName()
                    . '" имеет не поддерживаемое расширение "' . $file->getClientOriginalExtension() . '"');
            }
        }

        /** @var string $conversionUuid */
        $conversionUuid = $this->eventBus->publish(new CreateNewCombineEvent($extension, $files));

        return $this->success(['uuid' => $conversionUuid]);
    }
}
