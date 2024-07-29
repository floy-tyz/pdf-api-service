<?php

namespace App\DataFixtures;

use App\Bus\EventBusInterface;
use App\Service\User\Event\MakeUserEvent;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Bundle\FixturesBundle\FixtureGroupInterface;
use Doctrine\Persistence\ObjectManager;
use Symfony\Component\DependencyInjection\ParameterBag\ParameterBagInterface;

class UserFixtures extends Fixture implements FixtureGroupInterface
{
    public function __construct(
        private readonly EventBusInterface $eventBus,
        private readonly ParameterBagInterface $parameterBag
    ) {
    }

    public function load(ObjectManager $manager): void
    {
        $this->eventBus->publish(
            new MakeUserEvent(
                $this->parameterBag->get('test_user_login'),
                $this->parameterBag->get('test_user_password'),
                ['ROLE_USER', 'ROLE_TEST']
            )
        );
    }

    public static function getGroups(): array
    {
        return [
            'test'
        ];
    }
}
