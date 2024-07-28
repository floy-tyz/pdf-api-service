<?php

namespace App\Service\Process\Map;

class ProcessMap
{
    const string IMG_TO_PDF = 'img_to_pdf';
    const string OFFICE_TO_PDF = 'office_to_pdf';

    const array SUPPORTED_PROCESS_TYPES = [
        self::IMG_TO_PDF => [
            'available_extensions' => [
                FilesFormatMap::TYPE_PNG['extension'],
                FilesFormatMap::TYPE_JPEG['extension'],
                FilesFormatMap::TYPE_JPG['extension'],
                FilesFormatMap::TYPE_WEBP['extension'],
            ],
            'available_mime_types' => [
                FilesFormatMap::TYPE_PNG['mime_type'],
                FilesFormatMap::TYPE_JPEG['mime_type'],
                FilesFormatMap::TYPE_JPG['mime_type'],
                FilesFormatMap::TYPE_WEBP['mime_type'],
            ],
            'extension' => FilesFormatMap::TYPE_PDF['extension'],
            'context' => [
                ProcessContextMap::MERGE,
                ProcessContextMap::OPTIMIZE,
            ],
        ],
        self::OFFICE_TO_PDF => [
            'available_extensions' => [
                FilesFormatMap::TYPE_DOC['extension'],
                FilesFormatMap::TYPE_DOCX['extension'],
                FilesFormatMap::TYPE_XLS['extension'],
                FilesFormatMap::TYPE_XLSX['extension'],
            ],
            'available_mime_types' => [
                FilesFormatMap::TYPE_DOC['mime_type'],
                FilesFormatMap::TYPE_DOCX['mime_type'],
                FilesFormatMap::TYPE_XLS['mime_type'],
                FilesFormatMap::TYPE_XLSX['mime_type'],
            ],
            'extension' => FilesFormatMap::TYPE_PDF['extension'],
            'context' => [
                ProcessContextMap::OPTIMIZE,
            ],
        ],
    ];

    /**
     * Supported process types
     * @return array
     */
    public function __invoke(): array
    {
        return array_keys(self::SUPPORTED_PROCESS_TYPES);
    }
}