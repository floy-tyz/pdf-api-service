<?php

namespace App\Service\Conversion\Map;

class ConversionMap
{
    const string TYPE_PDF = 'pdf';
    const string TYPE_DOC = 'doc';
    const string TYPE_DOCX = 'docx';
    const string TYPE_XLS = 'xls';
    const string TYPE_XLSX = 'xlsx';
    const string TYPE_PNG = 'png';
    const string TYPE_JPEG = 'jpeg';
    const string TYPE_JPG = 'jpg';

    const array SUPPORTED_TYPE_TO_TYPE_CONVERTS = [
        self::TYPE_DOC => [
            self::TYPE_PDF, self::TYPE_DOCX, self::TYPE_XLS, self::TYPE_XLSX
        ],
        self::TYPE_DOCX => [
            self::TYPE_PDF, self::TYPE_DOC, self::TYPE_XLS, self::TYPE_XLSX
        ],
        self::TYPE_PDF => [
            self::TYPE_DOCX, self::TYPE_DOC, self::TYPE_XLS, self::TYPE_XLSX
        ]
    ];

    const array SUPPORTED_TYPES_TO_TYPE_COMBINES = [
        self::TYPE_PDF => [
            self::TYPE_JPG, self::TYPE_JPEG, self::TYPE_PNG
        ],
    ];
}