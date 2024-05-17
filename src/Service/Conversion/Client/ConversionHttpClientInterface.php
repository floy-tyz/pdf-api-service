<?php

/** @noinspection PhpMultipleClassDeclarationsInspection */

namespace App\Service\Conversion\Client;

use App\Entity\File;
use JsonException;
use Symfony\Component\DependencyInjection\ParameterBag\ParameterBagInterface;
use Symfony\Component\Mime\Part\DataPart;
use Symfony\Component\Mime\Part\Multipart\FormDataPart;
use Symfony\Contracts\HttpClient\Exception\ClientExceptionInterface;
use Symfony\Contracts\HttpClient\Exception\RedirectionExceptionInterface;
use Symfony\Contracts\HttpClient\Exception\ServerExceptionInterface;
use Symfony\Contracts\HttpClient\Exception\TransportExceptionInterface;
use Symfony\Contracts\HttpClient\HttpClientInterface;

readonly class ConversionHttpClientInterface implements ConversionClientInterface
{

    public function __construct(
        private HttpClientInterface $conversionClient,
        private ParameterBagInterface $parameterBag,
    ) {
    }

    /**
     * @param string $conversionUuid
     * @param string $convertExtension
     * @param array<File> $conversionFiles
     * @return mixed
     * @throws JsonException
     * @throws TransportExceptionInterface
     * @throws ClientExceptionInterface
     * @throws RedirectionExceptionInterface
     * @throws ServerExceptionInterface
     */
    public function requestConvertFiles(string $conversionUuid, string $convertExtension, array $conversionFiles): mixed
    {
        $formFields = [
            'uuid' => $conversionUuid,
        ];

        foreach ($conversionFiles as $file) {
            $formFields[$file->getUuidFileName()] = DataPart::fromPath(
                $this->parameterBag->get('kernel.project_dir')
                . DIRECTORY_SEPARATOR
                . $file->getPath());
        }

        $formData = new FormDataPart($formFields);

        $content = $this->conversionClient->request(
            'POST',
            'api/v1/convert',
            [
                'headers' => $formData->getPreparedHeaders()->toArray(),
                'body' => $formData->bodyToIterable(),
            ]
        );

        return json_decode($content->getContent(false), true, 512, JSON_THROW_ON_ERROR);
    }
}