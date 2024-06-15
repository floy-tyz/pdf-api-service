<?php

namespace App\Controller\Centrifugo;

use App\Traits\ResponseStatusTrait;
use Fresh\CentrifugoBundle\Service\Credentials\CredentialsGenerator;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class CentrifugoApiController extends AbstractController
{
    use ResponseStatusTrait;

    public function __construct(
        private readonly CredentialsGenerator $credentialsGenerator,
    ) {
    }

    #[Route('/api/v1/centrifugo/token/anonymous', name: 'api.centrifugo.get.token', methods: [Request::METHOD_GET])]
    public function getFile(): Response
    {
        $token = $this->credentialsGenerator->generateJwtTokenForAnonymous();

        return $this->success(['data' => ['token' => $token]]);
    }
}
