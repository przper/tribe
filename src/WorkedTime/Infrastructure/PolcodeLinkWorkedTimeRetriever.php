<?php

namespace Przper\Tribe\WorkedTime\Infrastructure;

use GuzzleHttp\ClientInterface;
use Przper\Tribe\WorkedTime\Domain\Date;
use Przper\Tribe\WorkedTime\Domain\Month;
use Przper\Tribe\WorkedTime\Domain\Time;
use Przper\Tribe\WorkedTime\Domain\TimeRange;
use Przper\Tribe\WorkedTime\Domain\WorkingDay;
use Przper\Tribe\WorkedTime\Domain\WorkingMonth;

class PolcodeLinkWorkedTimeRetriever
{
    private const POLCODE_WORKED_TIME_API = 'https://link.polcode.com/api/time/';

    public function __construct(
        private readonly string $linkApiKey,
        private readonly ClientInterface $httpClient,
    ) {}

    public function retrieve(\DateTimeInterface $start, \DateTimeInterface $end): WorkingMonth
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

        $dateRange = new \DatePeriod(
            $start,
            new \DateInterval('P1D'),
            $end,
            \DatePeriod::INCLUDE_END_DATE,
        );

        $workingMonth = WorkingMonth::create(Month::May);

        foreach ($dateRange as $date) {
            $day = Date::fromString($date->format(Date::DATE_FORMAT));
            $response = $this->httpClient->request(
                'GET',
                $this->getLinkForDay($day),
                ['headers' => ['Authorization' => $this->linkApiKey]],
            );

            $responseData = json_decode($response->getBody(), true);

            if ($responseData === []) {
                continue;
            }

            $workingDay = WorkingDay::create($day);

            foreach ($responseData as $item) {
                $start = new \DateTimeImmutable($item['fromDate']);
                $end = new \DateTimeImmutable($item['toDate']);

                $timeRange = TimeRange::create(
                    Time::fromString($start->format('H:i')),
                    Time::fromString($end->format('H:i')),
                );

                $workingDay->add($timeRange);
            }

            $workingMonth->add($workingDay);
        }

        return $workingMonth;
    }

    private function getLinkForDay(Date $date): string
    {
        return self::POLCODE_WORKED_TIME_API . $date;
    }
}
