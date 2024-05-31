<?php

require 'index.php';

use Przper\Tribe\WorkedTime\Infrastructure\PolcodeLinkWorkedTimeRetriever;
use Przper\Tribe\WorkedTime\Domain\Month;

$timeRetriever = new PolcodeLinkWorkedTimeRetriever($_ENV['POLCODE_LINK_APIKEY']);

$workingMonth = $timeRetriever->retrieve(2024, Month::May);

var_dump((string) $workingMonth->getWorkedTimeDuration());
