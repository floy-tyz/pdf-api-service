<?php

namespace App\Command;

use App\Bus\EventBus;
use App\Service\User\Event\MakeUserEvent;
use Exception;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;

#[AsCommand(
    name: 'app:make:user',
    description: 'Add a short description for your command',
)]
class MakeUserCommand extends Command
{
    public function __construct(
        private readonly EventBus $eventBus
    ) {
        parent::__construct();
    }

    protected function configure(): void
    {
        $this
            ->addOption('login', 'l', InputOption::VALUE_REQUIRED, 'Логин')
            ->addOption('password', 'p', InputOption::VALUE_REQUIRED, 'Пароль')
            ->addOption('roles', 'r', InputOption::VALUE_OPTIONAL, 'Роли')
        ;
    }

    /**
     * @throws Exception
     */
    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $login = $input->getOption('login');
        if (!$login) {
            throw new Exception('Логин не задан');
        }

        $password = $input->getOption('password');
        if (!$password) {
            throw new Exception('Пароль не задан');
        }

        $roles = array_map(fn(string $role) => trim($role), explode(',', trim($input->getOption('roles')))) ?? ['ROLE_USER'];

        $this->eventBus->publish(
            new MakeUserEvent(
                $login,
                $password,
                $roles
            )
        );

        $output->writeln("Успех");

        return Command::SUCCESS;
    }
}
