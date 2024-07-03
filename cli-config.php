<?php

# This is script for `vendor/bin/doctrine-migrations` to run

use Doctrine\DBAL\DriverManager;
use Doctrine\DBAL\Tools\DsnParser;
use Doctrine\Migrations\Configuration\Connection\ExistingConnection;
use Doctrine\Migrations\Configuration\Migration\YamlFile;
use Doctrine\Migrations\DependencyFactory;
use Symfony\Component\Dotenv\Dotenv;

(new Dotenv())->load(__DIR__ . '/.env');

$config = new YamlFile('migrations.yaml');

$connectionParams = (new DsnParser())->parse($_ENV['DATABASE_URL']);
$connection = DriverManager::getConnection($connectionParams);

return DependencyFactory::fromConnection($config, new ExistingConnection($connection));
