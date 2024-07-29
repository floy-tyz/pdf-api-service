<?php

namespace App\Tests\Integration\Controller;

use App\Request\Http\HttpMethod;
use App\Tests\Shared\TestCase\ApiEndPointTestCase;
use Exception;
use Symfony\Contracts\HttpClient\Exception\ClientExceptionInterface;
use Symfony\Contracts\HttpClient\Exception\RedirectionExceptionInterface;
use Symfony\Contracts\HttpClient\Exception\ServerExceptionInterface;

class CentrifugoApiControllerTest extends ApiEndPointTestCase
{
    /**
     * @throws ServerExceptionInterface
     * @throws RedirectionExceptionInterface
     * @throws ClientExceptionInterface
     * @throws Exception
     */
    public function testSuccessAuthentication(): void
    {
        $client = self::createClient();

        $client->jsonRequest(HttpMethod::GET->value, '/api/v1/centrifugo/token/anonymous');

        $response = $this->validateResponse($client);

        $this->assertArrayHasKey('token', $response['data']);
    }
}