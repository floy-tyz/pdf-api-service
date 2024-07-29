<?php

namespace App\Controller\Centrifugo;

use App\Traits\ResponseStatusTrait;
use Fresh\CentrifugoBundle\Service\Credentials\CredentialsGenerator;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use OpenApi\Attributes as OA;

#[OA\Tag(name: 'Центрифуга')]
class CentrifugoApiController extends AbstractController
{
    use ResponseStatusTrait;

    public function __construct(
        private readonly CredentialsGenerator $credentialsGenerator,
    ) {
    }

    #[Route('/api/v1/centrifugo/token/anonymous', name: 'api.centrifugo.get.token', methods: [Request::METHOD_GET])]
    #[OA\Get(
        summary: 'Получение токена центрифуги',
        responses: [
            new OA\Response(
                response: 200,
                description: 'Получение токена центрифуги',
                content: new OA\JsonContent(ref: '#/components/schemas/centrifugo-anonymous-token-response')
            ),
        ]
    )]
    public function getFile(): Response
    {
        $token = $this->credentialsGenerator->generateJwtTokenForAnonymous();

        return $this->success(['token' => $token]);
    }
}
