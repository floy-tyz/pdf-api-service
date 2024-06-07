<?php

namespace App\Service\Conversion\Client;

interface ConversionClientInterface
{
    public function requestUploadConvertFiles(string $conversionUuid, string $extension, array $convertFiles);

    public function requestUploadCombineFiles(string $conversionUuid, string $extension, array $combineFiles);
}