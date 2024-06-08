<?php

namespace App\Controller\Api;

use App\Bus\EventBusInterface;
use App\Entity\Process;
use App\Entity\File;
use App\Service\Process\Enum\ProcessStatusEnum;
use App\Service\Process\Event\CreateNewProcessEvent;
use App\Service\Process\Request\UploadProcessFilesRequest;
use App\Service\File\Interface\FileRepositoryInterface;
use App\Traits\ResponseStatusTrait;
use Doctrine\ORM\EntityNotFoundException;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class ProcessApiController extends AbstractController
{
    use ResponseStatusTrait;

    public function __construct(
        private readonly EventBusInterface $eventBus,
        private readonly FileRepositoryInterface $fileRepository,
    ) {
    }

    #[Route('/api/v1/process/files', name: 'api.process.upload.files', methods: ["POST"])]
    public function uploadProcessFiles(UploadProcessFilesRequest $request): Response
    {
        $dto = $request->getDto();

        $processUuid = $this->eventBus->publish(new CreateNewProcessEvent(
            $dto->getKey(),
            $dto->getExtension(),
            $dto->getFiles(),
            $dto->getContext()
        ));

        return $this->success(['uuid' => $processUuid]);
    }

    /**
     * @throws EntityNotFoundException
     */
    #[Route('/api/v1/process/{uuid}/files', name: 'api.process.get.files', methods: ["GET"])]
    public function getProcessedFiles(?Process $process): Response
    {
        if (!$process) {
            throw new EntityNotFoundException();
        }

        # todo replace with external validator
        if ($process->getStatus() !== ProcessStatusEnum::STATUS_PROCESSED->value) {
            return $this->failed();
        }

        /** @var array<File> $files */
        $files = $this->fileRepository->getProcessFiles($process->getId());

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
}
