<?php

use Przper\Tribe\WorkedTime\Infrastructure\PolcodeLinkWorkedTimeRetriever;
use Symfony\Component\HttpClient\CurlHttpClient;

require 'index.php';

$start = new \DateTimeImmutable('first day of this month');
$end = new \DateTimeImmutable('last day of this month');

echo "Checking {$start->format('Y-m-d')} - {$end->format('Y-m-d')} period\n\n";

$timeRetriever = new PolcodeLinkWorkedTimeRetriever($_ENV['POLCODE_LINK_APIKEY'], new CurlHttpClient());

$workingMonth = $timeRetriever->retrieve($start, $end);

$messages = [];

$messages[] = "State for: " . date('Y-m-d H:i');
$messages[] = "* Days worked: " . count($workingMonth->getWorkingDays());
$messages[] = "* Total time worked: " . $workingMonth->getTotalWorkedTimeDuration();
$messages[] = "* Expected worked time: " . $workingMonth->getExpectedWorkedTimeDuration();

$timeDifference = $workingMonth->getTotalWorkedTimeDuration()->difference($workingMonth->getExpectedWorkedTimeDuration());
$messages[] = $workingMonth->getTotalWorkedTimeDuration()->isGreaterThan($workingMonth->getExpectedWorkedTimeDuration())
    ? "* You are $timeDifference ahead"
    : "* You are $timeDifference behind"
;

foreach ($messages as $message) {
    echo $message . "\n";
}
