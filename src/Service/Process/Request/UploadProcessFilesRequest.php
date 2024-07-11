<?php

namespace App\Service\Process\Request;

use App\Exception\BusinessException;
use App\Service\Process\Map\ProcessMap;
use App\Service\Process\Request\Constraint\ValidProcessExtension;
use App\Service\Process\Request\Dto\UploadProcessFilesRequestDto;
use Psr\Log\LoggerInterface;
use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Component\Serializer\Exception\ExceptionInterface;
use Symfony\Component\Serializer\Normalizer\DenormalizerInterface;
use Symfony\Component\Serializer\SerializerInterface;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Component\Validator\Validator\ValidatorInterface;

readonly class UploadProcessFilesRequest
{
    public function __construct(
        private RequestStack $request,
        private SerializerInterface&DenormalizerInterface $serializer,
        private ValidatorInterface $validator,
        private LoggerInterface $logger
    ) {
    }

    public function getDto()
    {
        $request = $this->request->getMainRequest();

        $this->logger->info('CLIENT_IP:' . $request->getClientIp());
        $this->logger->info('ORIGINAL:' . $request->headers->get('x-original-forwarded-for'));
        $this->logger->info('HEADERS:' . $request->headers);

        try {
            $dto = $this->serializer->denormalize([...$request->request->all(), ...$request->files->all()],
                UploadProcessFilesRequestDto::class,
            );
        } catch (ExceptionInterface $e) {
            throw new BusinessException($e->getMessage());
        }

        $violations = $this->validator->validate($dto);

        if ($violations->count()) {
            throw new BusinessException(
                null,
                200,
                array_map(static fn ($e) => $e->getMessage(), iterator_to_array($violations))
            );
        }

        $this->validateExtension($dto);
        $this->validateFiles($dto);

        return $dto;
    }

    private function validateExtension(UploadProcessFilesRequestDto $dto): void
    {
        $constraints = [
            new Assert\NotNull(message: '"extension" не должно быть null'),
            new Assert\NotBlank(message: '"extension" не должно быть пустым'),
            new Assert\Type('string'),
            new ValidProcessExtension(ProcessMap::SUPPORTED_PROCESS_TYPES[$dto->getKey()]['extension']),
        ];

        $this->validate($dto->getExtension(), $constraints);
    }

    private function validateFiles(UploadProcessFilesRequestDto $dto): void
    {
        $constraints = [
            new Assert\Count(min: 1, minMessage: 'Файлы не указаны'),
            new Assert\All([
                new Assert\File(
                    maxSize: "10M",
                    mimeTypes: ProcessMap::SUPPORTED_PROCESS_TYPES[$dto->getKey()]['available_mime_types'],
                    extensions: ProcessMap::SUPPORTED_PROCESS_TYPES[$dto->getKey()]['available_extensions'],
                )
            ]),
        ];

        $this->validate($dto->getFiles(), $constraints);
    }

    private function validate(mixed $data, array $constraints): void
    {
        $violations = $this->validator->validate($data, $constraints);

        if ($violations->count()) {
            throw new BusinessException(
                null,
                200,
                array_map(static fn ($e) => $e->getMessage(), iterator_to_array($violations))
            );
        }
    }
}