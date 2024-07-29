<?php

namespace App\Tests\Integration\Controller;

use App\Bus\AsyncBusInterface;
use App\Request\Http\HttpMethod;
use App\Service\Process\Map\FilesFormatMap;
use App\Service\Process\Map\ProcessMap;
use App\Tests\Shared\TestCase\ApiEndPointTestCase;
use Exception;
use Symfony\Component\DependencyInjection\Container;
use Symfony\Component\DependencyInjection\ParameterBag\ParameterBagInterface;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Contracts\HttpClient\Exception\ClientExceptionInterface;
use Symfony\Contracts\HttpClient\Exception\RedirectionExceptionInterface;
use Symfony\Contracts\HttpClient\Exception\ServerExceptionInterface;

class ProcessApiControllerTest extends ApiEndPointTestCase
{
    /**
     * @throws ServerExceptionInterface
     * @throws RedirectionExceptionInterface
     * @throws ClientExceptionInterface
     * @throws Exception
     */
    public function testProcessOfficeToPdf(): void
    {
        $client = self::createClient();
        $container = self::getContainer();
        $this->mockAsyncBus($container);

        /** @var ParameterBagInterface $parameterBag */
        $parameterBag = $container->get(ParameterBagInterface::class);

        $uploadedFile = new UploadedFile(
            $parameterBag->get('kernel.project_dir') . DIRECTORY_SEPARATOR . '/tests/Integration/Controller/Data/test.docx',
            'test.docx',
            'application/docx',
            null,
            true
        );

        $client->request(HttpMethod::POST->value, '/api/v1/process/files',
            [
                'key' => ProcessMap::OFFICE_TO_PDF,
                'extension' => FilesFormatMap::TYPE_PDF['extension'],
            ],
            [
                'files' => [$uploadedFile],
            ],
            [
                'CONTENT_TYPE' => 'multipart/form-data',
            ]
        );

        $response = $this->validateResponse($client);
        $this->assertArrayHasKey('uuid', $response['data']);
        $this->assertIsUuid($response['data']['uuid']);
    }

    /**
     * @throws ServerExceptionInterface
     * @throws RedirectionExceptionInterface
     * @throws ClientExceptionInterface
     * @throws Exception
     */
    public function testProcessImageToPdf(): void
    {
        $client = self::createClient();
        $container = self::getContainer();
        $this->mockAsyncBus($container);

        /** @var ParameterBagInterface $parameterBag */
        $parameterBag = $container->get(ParameterBagInterface::class);

        $uploadedFile = new UploadedFile(
            $parameterBag->get('kernel.project_dir') . DIRECTORY_SEPARATOR . '/tests/Integration/Controller/Data/test.png',
            'test.png',
            'image/docx',
            null,
            true
        );

        $client->request(HttpMethod::POST->value, '/api/v1/process/files',
            [
                'key' => ProcessMap::IMG_TO_PDF,
                'extension' => FilesFormatMap::TYPE_PDF['extension'],
            ],
            [
                'files' => [$uploadedFile],
            ],
            [
                'CONTENT_TYPE' => 'multipart/form-data',
            ]
        );

        $response = $this->validateResponse($client);
        $this->assertArrayHasKey('uuid', $response['data']);
        $this->assertIsUuid($response['data']['uuid']);
    }

    /**
     * @throws ServerExceptionInterface
     * @throws RedirectionExceptionInterface
     * @throws ClientExceptionInterface
     * @throws Exception
     */
    public function testFailedProcess(): void
    {
        $client = self::createClient();
        $container = self::getContainer();

        /** @var ParameterBagInterface $parameterBag */
        $parameterBag = $container->get(ParameterBagInterface::class);

        $uploadedFile = new UploadedFile(
            $parameterBag->get('kernel.project_dir') . DIRECTORY_SEPARATOR . '/tests/Integration/Controller/Data/test.png',
            'test.png',
            'image/docx',
            null,
            true
        );

        $client->request(HttpMethod::POST->value, '/api/v1/process/files',
            [
                'key' => 'mp4',
                'extension' => FilesFormatMap::TYPE_PDF['extension'],
            ],
            [
                'files' => [$uploadedFile],
            ],
            [
                'CONTENT_TYPE' => 'multipart/form-data',
            ]
        );

        $response = $this->validateFailedResponse($client);
        $this->assertSame('Недопустимое расширение для конвертации', $response['message']);
    }

    private function mockAsyncBus(Container $container): void
    {
        $asyncBus = $this->createMock(AsyncBusInterface::class);
        $asyncBus->expects(self::once())
            ->method('dispatch')
            ->willReturn(true)
        ;
        $container->set(AsyncBusInterface::class, $asyncBus);
    }
}