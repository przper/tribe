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
        /**
         * Request
         * curl -H "Authorization: TOPSECRET" https://link.polcode.com/api/time/2024-05-31
         *
         * Response
         * [
         *      {
         *          "id":119744,
         *          "categoryId":434,
         *          "categoryName":"Vertuoza",
         *          "fromDate":"2024-05-31T07:43:00+02:00",
         *          "toDate":"2024-05-31T15:25:00+02:00",
         *          "comment":"VS-3845, 2716, 3846",
         *          "minutes":462
         *      }
         * ]
         */
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
