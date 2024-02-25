<?php

use Doctrine\DBAL\DriverManager;
use Doctrine\DBAL\Tools\DsnParser;

require 'index.php';

$connectionParams = (new DsnParser())->parse($_ENV['DATABASE_URL']);
echo json_encode($connectionParams) . "\n";

$connection = DriverManager::getConnection($connectionParams);

$sql = "SELECT * FROM test";
$statement = $connection->prepare($sql);

$result = $statement->executeQuery();

echo json_encode($result) . "\n";
