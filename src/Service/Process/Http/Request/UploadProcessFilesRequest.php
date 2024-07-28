<?php

namespace App\Service\Process\Http\Request;

use App\Entity\User;
use App\Exception\BusinessException;
use App\Security\Request\AbstractRequestValidator;
use App\Service\Process\Http\Constraint\ValidProcessExtension;
use App\Service\Process\Http\Dto\UploadProcessFilesRequestDto;
use App\Service\Process\Interface\ProcessRepositoryInterface;
use App\Service\Process\Map\ProcessMap;
use DateInterval;
use Psr\Log\LoggerInterface;
use Symfony\Bundle\SecurityBundle\Security;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Validator\Constraints as Assert;

final class UploadProcessFilesRequest extends AbstractRequestValidator
{
    const string BYTES_1_GB = '1073741824';

    public function __construct(
        private readonly LoggerInterface $logger,
        private readonly ProcessRepositoryInterface $processRepository,
        private readonly Security $security,
    ) {
    }

    public function getDto(): UploadProcessFilesRequestDto
    {
        $request = $this->request->getMainRequest();

        $this->validateProcessesLimits($request->getClientIp());

        /** @var UploadProcessFilesRequestDto $dto */
        $dto = $this->deserializeRequest($request, UploadProcessFilesRequestDto::class);

        $this->validate($dto);
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
            throw new BusinessException('Превышен лимит размера конвертации в течении ' . $time . ' минут,
             попробуйте чуть позже или зарегистрируйтесь!');
        }
    }

    private function validateExtension(UploadProcessFilesRequestDto $dto): void
    {
        $constraints = [
            new ValidProcessExtension(ProcessMap::SUPPORTED_PROCESS_TYPES[$dto->getKey()]['extension']),
        ];

        $this->validate($dto->getExtension(), $constraints);
    }

    private function validateFiles(UploadProcessFilesRequestDto $dto): void
    {
        $constraints = [
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
}