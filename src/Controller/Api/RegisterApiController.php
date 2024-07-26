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
use App\Service\User\Event\MakeUserEvent;
use App\Traits\ResponseStatusTrait;
use DateInterval;
use DateTime;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class RegisterApiController extends AbstractController
{
    use ResponseStatusTrait;

    public function __construct(
        private readonly EventBusInterface $eventBus,
        private readonly FileRepositoryInterface $fileRepository,
        private readonly SerializerInterface $serializer
    ) {
    }

    #[Route('/api/register', name: 'api.register', methods: ["POST"])]
    public function uploadProcessFiles(Request $request): Response
    {
        #todo add extra validator

        $this->eventBus->publish(new MakeUserEvent(
            $request->request->get('username'),
            $request->request->get('password'),
            ['ROLE_USER']
        ));

        return $this->success();
    }
}
