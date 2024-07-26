<?php

namespace App\Service\Process\Request;

use App\Entity\User;
use App\Exception\BusinessException;
use App\Service\Process\Interface\ProcessRepositoryInterface;
use App\Service\Process\Map\ProcessMap;
use App\Service\Process\Request\Constraint\ValidProcessExtension;
use App\Service\Process\Request\Dto\UploadProcessFilesRequestDto;
use DateInterval;
use Exception;
use Psr\Log\LoggerInterface;
use Symfony\Bundle\SecurityBundle\Security;
use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Serializer\Exception\ExceptionInterface;
use Symfony\Component\Serializer\Normalizer\DenormalizerInterface;
use Symfony\Component\Serializer\SerializerInterface;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Component\Validator\Validator\ValidatorInterface;

readonly class UploadProcessFilesRequest
{
    const string BYTES_1_GB = '1';

    public function __construct(
        private RequestStack $request,
        private SerializerInterface&DenormalizerInterface $serializer,
        private ValidatorInterface $validator,
        private LoggerInterface $logger,
        private ProcessRepositoryInterface $processRepository,
        private Security $security,
    ) {
    }

    public function getDto()
    {
        $request = $this->request->getMainRequest();

        $this->validateProcessesLimits($request->getClientIp());

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
                array_map(static fn($e) => $e->getMessage(), iterator_to_array($violations))
            );
        }

        $this->validateExtension($dto);
        $this->validateFiles($dto);

        $dto->setClientIp($request->getClientIp());

        return $dto;
    }

    private function validateProcessesLimits(string $clientIp): void
    {
        $this->logger->info('CLIENT_IP: ' . $clientIp);

        /** @var User|UserInterface|null $user */
        $user = $this->security->getUser();

        if ($user instanceof UserInterface && $user->getActive()) {
            $size = $this->processRepository->getSizeSumOfProcessedFilesByUser($user);
            $time = '3';
        }
        else {
            $size = $this->processRepository->getSizeSumOfProcessedFilesByAnonymous($clientIp, new DateInterval('PT10M'));
            $time = '10';
        }

        if (bccomp($size, self::BYTES_1_GB) === 1) {
            throw new BusinessException('Превышен лимит размера конвертации (1 Гб) в течении ' . $time . ' минут,
             попробуйте чуть позже или зарегистрируйтесь!');
        }
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
                ),
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
                array_map(static fn($e) => $e->getMessage(), iterator_to_array($violations))
            );
        }
    }
}