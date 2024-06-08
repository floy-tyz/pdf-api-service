<?php

namespace App\Service\Process\Map;

class FilesFormatMap
{
    /**
     * @var array<string, string>
     */
    const array TYPE_PDF = [
        'extension' => 'pdf',
        'mime_type' => 'application/pdf'
    ];

    /**
     * @var array<string, string>
     */
    const array TYPE_DOCX = [
        'extension' => 'docx',
        'mime_type' => 'application/docx'
    ];

    /**
     * @var array<string, string>
     */
    const array TYPE_DOC = [
        'extension' => 'doc',
        'mime_type' => 'application/doc'
    ];

    /**
     * @var array<string, string>
     */
    const array TYPE_XLS = [
        'extension' => 'xls',
        'mime_type' => 'application/xls'
    ];

    /**
     * @var array<string, string>
     */
    const array TYPE_XLSX = [
        'extension' => 'xlsx',
        'mime_type' => 'application/xlsx'
    ];

    /**
     * @var array<string, string>
     */
    const array TYPE_PNG = [
        'extension' => 'png',
        'mime_type' => 'image/png'
    ];

    /**
     * @var array<string, string>
     */
    const array TYPE_JPEG = [
        'extension' => 'jpeg',
        'mime_type' => 'image/jpeg'
    ];

    /**
     * @var array<string, string>
     */
    const array TYPE_JPG = [
        'extension' => 'jpg',
        'mime_type' => 'image/jpg'
    ];

    /**
     * @var array<string, string>
     */
    const array TYPE_WEBP = [
        'extension' => 'webp',
        'mime_type' => 'image/webp'
    ];
}