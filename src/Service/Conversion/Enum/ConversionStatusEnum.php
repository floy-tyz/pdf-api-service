<?php

namespace App\Service\Conversion\Enum;

enum ConversionStatusEnum: string
{
    case STATUS_CREATED = 'created';

    case STATUS_SENT_TO_CONVERT_SERVICE = 'sent_to_convert';

    case STATUS_CONVERTED = 'converted';

    case STATUS_FAILED = 'failed';
}
