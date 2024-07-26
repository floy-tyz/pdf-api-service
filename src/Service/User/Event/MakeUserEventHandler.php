<?php

namespace App\Service\User\Event;

use App\Bus\AsyncBusInterface;
use App\Bus\EventBusInterface;
use App\Bus\EventHandlerInterface;
use App\Entity\File;
use App\Entity\Process;
use App\Entity\User;
use App\Repository\UserRepository;
use App\Service\File\Event\PutFileToStorageEvent;
use App\Service\File\Event\SaveFileEntityFromPathEvent;
use App\Service\Process\Event\CreateNewProcessEvent;
use App\Service\Process\Event\External\ProcessFilesEvent;
use App\Service\Process\Interface\ProcessRepositoryInterface;
use App\Service\User\Interface\UserRepositoryInterface;
use Symfony\Bundle\SecurityBundle\Security;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\PasswordHasher\PasswordHasherInterface;
use Symfony\Component\Security\Core\User\UserInterface;
use function Symfony\Component\Translation\t;

readonly class MakeUserEventHandler implements EventHandlerInterface
{
    public function __construct(
        private UserRepositoryInterface $repository,
        private UserPasswordHasherInterface $hasher,
    ) {
    }

    /**
     */
    public function __invoke(MakeUserEvent $event): void
    {
        $user = new User();

        $user->setLogin($event->getLogin());
        $user->setPassword($this->hasher->hashPassword($user, $event->getPassword()));
        $user->setRoles($event->getRoles());

        $this->repository->save($user);
    }
}