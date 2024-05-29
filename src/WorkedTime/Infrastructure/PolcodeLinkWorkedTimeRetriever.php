<?php

namespace Przper\Tribe\WorkedTime\Infrastructure;

use GuzzleHttp\Client;
use Przper\Tribe\WorkedTime\Domain\Date;

class PolcodeLinkWorkedTimeRetriever
{
    private const POLCODE_WORKED_TIME_API = 'https://link.polcode.com/api/time/';

    public function __construct(
        private readonly string $linkApiKey,
    ) {}

    public function retrieve(Date $start, ?Date $end = null)
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

    private function getLinkForDay(Date $date): string
    {
        return self::POLCODE_WORKED_TIME_API . $date;
    }
}
