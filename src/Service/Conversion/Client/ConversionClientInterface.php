<?php

namespace App\Service\Conversion\Client;

interface ConversionClientInterface
{
    public function requestConvertFiles(string $conversionUuid, string $convertExtension, array $conversionFiles);
}