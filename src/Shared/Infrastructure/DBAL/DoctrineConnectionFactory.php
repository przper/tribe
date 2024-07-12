<?php

namespace Przper\Tribe\Shared\Infrastructure\DBAL;

use Doctrine\DBAL\Connection;
use Doctrine\DBAL\DriverManager;
use Doctrine\DBAL\Tools\DsnParser;

class DoctrineConnectionFactory
{
    public static function createConnection(string $databaseUrl): Connection
    {
        $connectionParams = (new DsnParser())->parse($databaseUrl);

        return DriverManager::getConnection($connectionParams);
    }
}
