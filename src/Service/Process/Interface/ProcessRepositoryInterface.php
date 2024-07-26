<?php

namespace App\Service\Process\Interface;

use App\Entity\User;
use App\Repository\RepositoryInterface;
use DateInterval;
use Symfony\Component\Security\Core\User\UserInterface;

interface ProcessRepositoryInterface extends RepositoryInterface
{
    /**
     * @param UserInterface|User $user
     * @return string bytes
     */
    public function getSizeSumOfProcessedFilesByUser(
        UserInterface|User $user,
    ): string;

    /**
     * @param string $clientIp
     * @param DateInterval $dateInterval
     * @return string bytes
     */
    public function getSizeSumOfProcessedFilesByAnonymous(
        string $clientIp,
        DateInterval $dateInterval,
    ): string;
}

