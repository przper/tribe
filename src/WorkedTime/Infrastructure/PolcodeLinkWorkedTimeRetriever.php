<?php

namespace Przper\Tribe\WorkedTime\Infrastructure;

use Przper\Tribe\WorkedTime\Domain\Timestamp;
use GuzzleHttp\Client;

class PolcodeLinkWorkedTimeRetriever
{
    private const POLCODE_WORKED_TIME_API = 'https://link.polcode.com/api/time/';
    public function __construct(
        private readonly $linkApiKey,
    ) {}

    public function retrieve(Timestamp $start, ?Timestamp $end = null)
    {
        $httpClient = new Client();

        $days = [$start];

        foreach ($days as $day) {
            $response = $httpClient->request(
                'GET',
                $this->getLinkForDay($day),
                ['headers' => ['Authorization' => $this->linkApiKey]],
            );
        }
    }

    private function getLinkForDay(Timestamp $timestamp): string
    {
        return self::POLCODE_WORKED_TIME_API . $timestamp;
    }
}
