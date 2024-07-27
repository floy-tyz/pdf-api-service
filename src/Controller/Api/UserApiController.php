<?php

namespace App\Controller\Api;

use App\Bus\EventBusInterface;
use App\Serializer\SerializerInterface;
use App\Service\File\Interface\FileRepositoryInterface;
use App\Service\User\Event\MakeUserEvent;
use App\Service\User\Http\Request\RegisterUserRequest;
use App\Traits\ResponseStatusTrait;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class UserApiController extends AbstractController
{
    use ResponseStatusTrait;

    public function __construct(
        private readonly EventBusInterface $eventBus,
        private readonly FileRepositoryInterface $fileRepository,
        private readonly SerializerInterface $serializer
    ) {
    }

    #[Route('/api/register', name: 'api.register', methods: ["POST"])]
    public function uploadProcessFiles(RegisterUserRequest $request): Response
    {
        $dto = $request->getDto();

        $this->eventBus->publish(new MakeUserEvent(
            $dto->getUsername(),
            $dto->getPassword(),
            ['ROLE_USER']
        ));

        return $this->success();
    }
}
