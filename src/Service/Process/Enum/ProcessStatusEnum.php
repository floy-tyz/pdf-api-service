<?php

namespace App\Service\Process\Enum;

enum ProcessStatusEnum: string
{
    case STATUS_CREATED = 'created';

    case STATUS_SENT = 'sent';

    case STATUS_PROCESSED = 'processed';

    case STATUS_FAILED = 'failed';
}
