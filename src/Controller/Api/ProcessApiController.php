<?php

namespace App\Controller\Api;

use App\Bus\EventBusInterface;
use App\Entity\Process;
use App\Entity\File;
use App\Exception\BusinessException;
use App\Serializer\SerializerInterface;
use App\Service\Process\Enum\ProcessStatusEnum;
use App\Service\Process\Event\CreateNewProcessEvent;
use App\Service\Process\Request\UploadProcessFilesRequest;
use App\Service\File\Interface\FileRepositoryInterface;
use App\Traits\ResponseStatusTrait;
use DateInterval;
use DateTime;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class ProcessApiController extends AbstractController
{
    use ResponseStatusTrait;

    public function __construct(
        private readonly EventBusInterface $eventBus,
        private readonly FileRepositoryInterface $fileRepository,
        private readonly SerializerInterface $serializer
    ) {
    }

    #[Route('/api/v1/process/files', name: 'api.process.upload.files', methods: ["POST"])]
    public function uploadProcessFiles(UploadProcessFilesRequest $request): Response
    {
        $dto = $request->getDto();

        $processUuid = $this->eventBus->publish(new CreateNewProcessEvent(
            $dto->getKey(),
            $dto->getExtension(),
            $dto->getClientIp(),
            $dto->getFiles(),
            $dto->getContext()
        ));

        return $this->success(['uuid' => $processUuid]);
    }

    #[Route('/api/v1/process/{uuid}/files', name: 'api.process.get.files', methods: ["GET"])]
    public function getProcessedFiles(?Process $process): Response
    {
        # todo replace with external validator
        if (!$process) {
            throw new BusinessException('Конвертация не найдена');
        }

        if ($process->getDateProcessed()) {
            $ttl = ($process->getDateProcessed())->add(new DateInterval('PT1M'));
            if (new DateTime() > $ttl) {
                throw new BusinessException('Сконвертированные файлы были очищены');
            }
        }

        if ($process->getStatus() !== ProcessStatusEnum::STATUS_PROCESSED->value) {
            throw new BusinessException('Конвертация еще не окончена');
        }

        $files = $this->serializer->normalize(
            $this->fileRepository->getProcessFiles($process->getId()),
            File::class,
            ['groups' => ['files']]
        );

        return $this->success(['files' => $files]);
    }
}
