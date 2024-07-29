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

class ProcessMapApiControllerTest extends ApiEndPointTestCase
{
    /**
     * @throws ServerExceptionInterface
     * @throws RedirectionExceptionInterface
     * @throws ClientExceptionInterface
     * @throws Exception
     */
    public function testGetSupportedProcesses(): void
    {
        $client = self::createClient();

        $client->jsonRequest(HttpMethod::GET->value, '/api/v1/process/types');

        $response = $this->validateResponse($client);
        $this->assertSame(ProcessMap::SUPPORTED_PROCESS_TYPES, $response['data']);
    }
}