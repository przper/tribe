<?php

namespace Przper\Tribe\WorkedTime\Ports\Cli;

use GuzzleHttp\Client;
use Przper\Tribe\WorkedTime\Domain\Month;
use Przper\Tribe\WorkedTime\Infrastructure\PolcodeLinkWorkedTimeRetriever;

$timeRetriever = new PolcodeLinkWorkedTimeRetriever($_ENV['POLCODE_LINK_APIKEY'], new Client());

$workingMonth = $timeRetriever->retrieve(
    new \DateTimeImmutable('2024-06-01'),
    new \DateTimeImmutable('2024-06-10'),
);

//var_dump($workingMonth->getWorkingDays());
var_dump((string) $workingMonth->getExpectedWorkedTimeDuration());
var_dump((string) $workingMonth->getTotalWorkedTimeDuration());
