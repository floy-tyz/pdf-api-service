<?php

declare(strict_types=1);

namespace App\Tests\Shared\TestCase;

use App\Service\User\Interface\UserRepositoryInterface;
use App\Tests\Shared\Trait\WebAssertTrait;
use Exception;
use Symfony\Bundle\FrameworkBundle\KernelBrowser;
use Symfony\Bundle\FrameworkBundle\Test\WebTestAssertionsTrait;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase as SymfonyWebTestCase;
use Symfony\Component\DependencyInjection\ParameterBag\ParameterBagInterface;
use Symfony\Component\HttpFoundation\BinaryFileResponse;
use Symfony\Component\HttpFoundation\File\File;

abstract class ApiEndPointTestCase extends SymfonyWebTestCase
{
    use WebTestAssertionsTrait, WebAssertTrait;

    private array $fixtures = [];

    /**
     * @return KernelBrowser
     * @throws Exception
     */
    protected function createTestClient(): KernelBrowser
    {
        $client = SymfonyWebTestCase::createClient();

        /** @var ParameterBagInterface $parameterBag */
        $parameterBag = static::getContainer()->get(ParameterBagInterface::class);

        $client->setServerParameters([
            'HTTP_HOST' => $parameterBag->get('http_host')
        ]);

        /** @var UserRepositoryInterface $userRepository */
        $userRepository = static::getContainer()->get(UserRepositoryInterface::class);

        $user = $userRepository->findOneBy(['guid' => $parameterBag->get('test_user_login')]);

        $client->loginUser($user);

        self::getClient($client);

        return $client;
    }

    /**
     * @param KernelBrowser $client
     * @return array
     */
    protected function validateResponse(KernelBrowser $client): array
    {
        $this->assertHttpResponseSuccess($client->getResponse());

        $response = json_decode($client->getResponse()->getContent(), true);

        if (!$response['success']) {
            dump($response);
        }

        $this->assertIsArray($response);
        $this->assertArrayHasKey('success', $response, $response['message'] ?? '');
        $this->assertTrue($response['success'], $response['message'] ?? '');

        return $response;
    }

    /**
     * @param KernelBrowser $client
     * @return array
     */
    protected function validateFailedResponse(KernelBrowser $client): array
    {
        $this->assertHttpResponseSuccess($client->getResponse());

        $response = json_decode($client->getResponse()->getContent(), true);

        $this->assertIsArray($response);
        $this->assertArrayHasKey('success', $response);
        $this->assertFalse($response['success']);

        return $response;
    }

    /**
     * @param KernelBrowser $client
     * @return File
     */
    protected function validateBinaryResponse(KernelBrowser $client): File
    {
        $this->assertHttpResponseSuccess($client->getResponse());

        /** @var BinaryFileResponse $response */
        $response = $client->getResponse();

        self::assertFileIsReadable($response->getFile()->getPathname());

        return $response->getFile();
    }

    /**
     * @throws Exception
     */
    protected function tearDown(): void
    {
        parent::tearDown();

        self::getClient(null);

        $this->fixtures = [];
    }
}
