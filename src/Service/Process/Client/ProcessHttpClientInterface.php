<?php

/** @noinspection PhpMultipleClassDeclarationsInspection */

namespace App\Service\Process\Client;

use App\Entity\File;
use App\Entity\Process;
use JsonException;
use Symfony\Component\DependencyInjection\ParameterBag\ParameterBagInterface;
use Symfony\Component\Mime\Part\DataPart;
use Symfony\Component\Mime\Part\Multipart\FormDataPart;
use Symfony\Contracts\HttpClient\Exception\ClientExceptionInterface;
use Symfony\Contracts\HttpClient\Exception\RedirectionExceptionInterface;
use Symfony\Contracts\HttpClient\Exception\ServerExceptionInterface;
use Symfony\Contracts\HttpClient\Exception\TransportExceptionInterface;
use Symfony\Contracts\HttpClient\HttpClientInterface;

readonly class ProcessHttpClientInterface implements ProcessClientInterface
{

    public function __construct(
        private HttpClientInterface $processClient,
        private ParameterBagInterface $parameterBag,
    ) {
    }

    /**
     * @param Process $process
     * @return mixed
     * @throws ClientExceptionInterface
     * @throws JsonException
     * @throws RedirectionExceptionInterface
     * @throws ServerExceptionInterface
     * @throws TransportExceptionInterface
     */
    public function requestProcessFiles(Process $process): mixed
    {
        $formFields = [
            'uuid' => $process->getUuid(),
            'key' => $process->getKey(),
            'extension' => $process->getExtension(),
            'context' => $process->getContext()
        ];

        foreach ($process->getFiles() as $file) {
            $formFields[$file->getUuidFileName()] = DataPart::fromPath(
                $this->parameterBag->get('kernel.project_dir')
                . DIRECTORY_SEPARATOR
                . $file->getPath());
        }

        $formData = new FormDataPart($formFields);

        $content = $this->processClient->request(
            'POST',
            'api/v1/process',
            [
                'headers' => $formData->getPreparedHeaders()->toArray(),
                'body' => $formData->bodyToIterable(),
            ]
        );

        return json_decode($content->getContent(), true, 512, JSON_THROW_ON_ERROR);
    }
}