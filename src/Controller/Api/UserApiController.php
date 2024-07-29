<?php

namespace App\Controller\Api;

use App\Bus\EventBusInterface;
use App\Request\Serializer\SerializerInterface;
use App\Service\File\Interface\FileRepositoryInterface;
use App\Service\User\Event\MakeUserEvent;
use App\Service\User\Http\Dto\AuthUserRequestDto;
use App\Service\User\Http\Dto\RegisterUserRequestDto;
use App\Service\User\Http\Request\AuthUserRequest;
use App\Service\User\Http\Request\RegisterUserRequest;
use App\Traits\ResponseStatusTrait;
use Nelmio\ApiDocBundle\Annotation\Model;
use OpenApi\Attributes as OA;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

#[OA\Tag(name: 'Пользователь')]
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
    #[OA\Post(
        summary: 'Регистрация нового пользователя',
        requestBody: new OA\RequestBody(content: new OA\JsonContent(ref: new Model(type: RegisterUserRequestDto::class))),
        responses: [
            new OA\Response(
                response: 200,
                description: 'Регистрация нового пользователя',
                content: new OA\JsonContent(ref: '#/components/schemas/success-empty-response')
            ),
        ]
    )]
    public function registerUser(RegisterUserRequest $request): Response
    {
        $dto = $request->getDto();

        $this->eventBus->publish(
            new MakeUserEvent(
                $dto->getUsername(),
                $dto->getPassword(),
                ['ROLE_USER']
            )
        );

        return $this->success();
    }

    #[Route('/api/auth', name: 'api.auth', methods: ["POST"])]
    #[OA\Post(
        summary: 'Получение bearer токена пользователя',
        requestBody: new OA\RequestBody(content: new OA\JsonContent(ref: new Model(type: AuthUserRequestDto::class))),
        responses: [
            new OA\Response(
                response: 200,
                description: 'Bearer токен',
                content: new OA\JsonContent(ref: '#/components/schemas/auth-user-response')
            ),
        ]
    )]
    public function authUser(AuthUserRequest $request): void
    {
    }
}
