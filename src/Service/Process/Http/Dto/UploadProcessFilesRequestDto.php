<?php

namespace App\Service\Process\Http\Dto;

use App\Service\Process\Map\FilesFormatMap;
use App\Service\Process\Map\ProcessMap;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\Serializer\Attribute\Ignore;
use Symfony\Component\Validator\Constraints as Assert;
use OpenApi\Attributes as OA;

class UploadProcessFilesRequestDto
{
    #[Ignore]
    private ?string $clientIp = null;

    /**
     * @param array<UploadedFile> $files
     * @param array<int, string> $context
     */
    public function __construct(
        #[Assert\Type('string')]
        #[Assert\NotNull]
        #[Assert\NotBlank]
        #[OA\Property(
            enum: [
                ProcessMap::IMG_TO_PDF,
                ProcessMap::OFFICE_TO_PDF,
            ],
            example: ProcessMap::IMG_TO_PDF,
            nullable: false
        )]
        private readonly string $key,

        #[Assert\Type('string')]
        #[Assert\NotNull]
        #[Assert\NotBlank]
        #[OA\Property(
            enum: [
                FilesFormatMap::TYPE_PDF['extension'],
            ],
            example: FilesFormatMap::TYPE_PDF['extension'],
            nullable: false
        )]
        private readonly string $extension,

        #[Assert\Count(min: 1, minMessage: 'Файлы не указаны')]
        #[OA\Property(
            property: 'files',
            type: 'object',
            nullable: false
        )]
        private readonly array $files,

        private readonly array $context = [],
    ) {
    }

    public function getKey(): string
    {
        return $this->key;
    }

    public function getExtension(): string
    {
        return $this->extension;
    }

    public function getFiles(): array
    {
        return $this->files;
    }

    public function getContext(): array
    {
        return $this->context;
    }

    public function getClientIp(): ?string
    {
        return $this->clientIp;
    }

    public function setClientIp(?string $clientIp): void
    {
        $this->clientIp = $clientIp;
    }
}