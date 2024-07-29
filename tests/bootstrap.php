<?php

use Symfony\Component\Dotenv\Dotenv;
use Symfony\Component\Filesystem\Filesystem;

require dirname(__DIR__).'/vendor/autoload.php';

if (method_exists(Dotenv::class, 'bootEnv')) {
    (new Dotenv())->bootEnv(dirname(__DIR__).'/.env');
}

if ($_SERVER['APP_DEBUG']) {
    umask(0000);
}

//(new Filesystem())->remove(__DIR__.'/../var/cache/test');

passthru(sprintf(
    'APP_ENV=%s php "%s/../bin/console" doctrine:database:create --if-not-exists',
    $_ENV['APP_ENV'], __DIR__
));

passthru(sprintf(
    'APP_ENV=%s php "%s/../bin/console" doctrine:migrations:migrate -n',
    $_ENV['APP_ENV'], __DIR__
));

passthru(sprintf(
    'APP_ENV=%s php "%s/../bin/console" doctrine:fixtures:load -n --group=test',
    $_ENV['APP_ENV'], __DIR__
));
