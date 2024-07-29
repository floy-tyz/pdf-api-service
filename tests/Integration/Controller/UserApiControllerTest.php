<?php

namespace App\Tests\Integration\Controller;

use App\Request\Http\HttpMethod;
use App\Tests\Shared\TestCase\ApiEndPointTestCase;
use Exception;
use Symfony\Component\DependencyInjection\ParameterBag\ParameterBagInterface;
use Symfony\Component\Uid\Uuid;
use Symfony\Contracts\HttpClient\Exception\ClientExceptionInterface;
use Symfony\Contracts\HttpClient\Exception\RedirectionExceptionInterface;
use Symfony\Contracts\HttpClient\Exception\ServerExceptionInterface;

class UserApiControllerTest extends ApiEndPointTestCase
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
        $container = self::getContainer();

        /** @var ParameterBagInterface $parameterBag */
        $parameterBag = $container->get(ParameterBagInterface::class);

        $client->jsonRequest(HttpMethod::POST->value, '/api/auth', [
            'username' => $parameterBag->get('test_user_login'),
            'password' => $parameterBag->get('test_user_password'),
        ]);

        $response = $this->validateResponse($client);

        $this->assertArrayHasKey('token', $response['data']);
    }

    /**
     * @throws ServerExceptionInterface
     * @throws RedirectionExceptionInterface
     * @throws ClientExceptionInterface
     * @throws Exception
     */
    public function testFailedAuthentication(): void
    {
        $client = self::createClient();

        $client->jsonRequest(HttpMethod::POST->value, '/api/auth', [
            'username' => 'failed',
            'password' => 'failed',
        ]);

        $this->assertHttpResponseBad($client->getResponse());

        $response = json_decode($client->getResponse()->getContent(), true);

        $this->assertIsArray($response);
        $this->assertArrayHasKey('code', $response);
        $this->assertSame(401, $response['code']);
    }

    /**
     * @throws ServerExceptionInterface
     * @throws RedirectionExceptionInterface
     * @throws ClientExceptionInterface
     * @throws Exception
     */
    public function testSuccessRegisterUser(): void
    {
        $client = self::createClient();
        $container = self::getContainer();

        /** @var ParameterBagInterface $parameterBag */
        $parameterBag = $container->get(ParameterBagInterface::class);

        $client->jsonRequest(HttpMethod::POST->value, '/api/register', [
            'username' => Uuid::v4(),
            'password' => $parameterBag->get('test_user_password'),
            'password_confirm' => $parameterBag->get('test_user_password'),
        ]);

        $this->validateResponse($client);
    }

    /**
     * @throws ServerExceptionInterface
     * @throws RedirectionExceptionInterface
     * @throws ClientExceptionInterface
     * @throws Exception
     */
    public function testFailedUniqueRegisterUser(): void
    {
        $client = self::createClient();
        $container = self::getContainer();

        /** @var ParameterBagInterface $parameterBag */
        $parameterBag = $container->get(ParameterBagInterface::class);

        $client->jsonRequest(HttpMethod::POST->value, '/api/register', [
            'username' => $parameterBag->get('test_user_login'),
            'password' => $parameterBag->get('test_user_password'),
            'password_confirm' => $parameterBag->get('test_user_password'),
        ]);

        $response = $this->validateFailedResponse($client);

        $this->assertArrayHasKey('errors', $response);
        $this->assertSame('Пользователь с таким логином уже существует', $response['errors']['username'][0]);
    }

    /**
     * @throws ServerExceptionInterface
     * @throws RedirectionExceptionInterface
     * @throws ClientExceptionInterface
     * @throws Exception
     */
    public function testFailedNotSamePasswordRegisterUser(): void
    {
        $client = self::createClient();
        $container = self::getContainer();

        /** @var ParameterBagInterface $parameterBag */
        $parameterBag = $container->get(ParameterBagInterface::class);

        $client->jsonRequest(HttpMethod::POST->value, '/api/register', [
            'username' => Uuid::v4(),
            'password' => $parameterBag->get('test_user_password'),
            'password_confirm' => '1234',
        ]);

        $response = $this->validateFailedResponse($client);

        $this->assertArrayHasKey('errors', $response);
        $this->assertSame('Пароли не совпадают', $response['errors']['password_confirm'][0]);
    }
}