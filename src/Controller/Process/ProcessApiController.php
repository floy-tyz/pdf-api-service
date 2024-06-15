<?php

namespace App\Controller\Process;

use App\Bus\EventBusInterface;
use App\Entity\File;
use App\Entity\Process;
use App\Serializer\SerializerInterface;
use App\Service\Process\Enum\ProcessStatusEnum;
use App\Service\Process\Event\SaveProcessedFilesEvent;
use App\Service\File\Interface\FileRepositoryInterface;
use App\Traits\ResponseStatusTrait;
use Doctrine\ORM\EntityNotFoundException;
use Exception;
use Fresh\CentrifugoBundle\Service\CentrifugoInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class ProcessApiController extends AbstractController
{
    use ResponseStatusTrait;

    public function __construct(
        private readonly EventBusInterface $eventBus,
        private readonly FileRepositoryInterface $fileRepository,
        private readonly CentrifugoInterface $centrifugo,
        private readonly SerializerInterface $serializer
    ) {
    }

    /**
     * @throws EntityNotFoundException
     * @throws Exception
     */
    #[Route('/api/v1/process/{uuid}/files', name: 'api.process.save.files', methods: ["POST"])]
    public function saveProcessedFiles(Request $request, ?Process $process): Response
    {
        if (!$process) {
            throw new EntityNotFoundException();
        }

        if ($process->getStatus() === ProcessStatusEnum::STATUS_PROCESSED->value) {
            return $this->success();
        }

        $this->eventBus->publish(new SaveProcessedFilesEvent($process, $request->files->all()));

        $files = $this->serializer->normalize(
            $this->fileRepository->getProcessFiles($process->getId()),
            File::class,
            ['groups' => ['files']]
        );

        $this->centrifugo->publish([
                'files' => $files
            ], $process->getUuid()
        );

        return $this->success();
    }
}
