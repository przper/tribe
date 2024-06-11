<?php

namespace Przper\Tribe\Tests\Unit\WorkedTime\Unit\Infrastructure;

use GuzzleHttp\Client;
use GuzzleHttp\Psr7\Response;
use PHPUnit\Framework\Attributes\Test;
use PHPUnit\Framework\TestCase;
use Przper\Tribe\WorkedTime\Infrastructure\PolcodeLinkWorkedTimeRetriever;

class PolcodeLinkWorkedTimeRetrieverTest extends TestCase
{
    #[Test]
    public function it_creates_WorkingMonth(): void
    {
        $linkApiKey = 'test link api key';
        $mockedHttpClient = $this->createMock(Client::class);
        $workedHoursDataMap = [
            [], //saturday
            [], //sunday
            [
                [
                    'id' => 120095,
                    'categoryId' => 434,
                    'categoryName' => 'Vertuoza',
                    'fromDate' => '2024-06-03T07:40:00+02:00',
                    'toDate' => '2024-06-03T15:54:00+02:00',
                    'comment' => "VS-3845, 2716, 3846",
                    'minutes' => 494,
                ],
            ],
            [
                [
                    'id' => 120212,
                    'categoryId' => 434,
                    'categoryName' => 'Vertuoza',
                    'fromDate' => '2024-06-04T07:46:00+02:00',
                    'toDate' => '2024-06-04T12:03:00+02:00',
                    'comment' => "VS-3847",
                    'minutes' => 257,
                ],
                [
                    'id' => 120213,
                    'categoryId' => 434,
                    'categoryName' => 'Vertuoza',
                    'fromDate' => '2024-06-04T13:10:00+02:00',
                    'toDate' => '2024-06-04T16:35:00+02:00',
                    'comment' => "VS-3847",
                    'minutes' => 205,
                ],
            ],
            [
                [
                    'id' => 120351,
                    'categoryId' => 434,
                    'categoryName' => 'Vertuoza',
                    'fromDate' => '2024-06-05T07:40:00+02:00',
                    'toDate' => '2024-06-05T16:08:00+02:00',
                    'comment' => "VS-3847",
                    'minutes' => 508,
                ],
            ],
            [
                [
                    'id' => 120758,
                    'categoryId' => 434,
                    'categoryName' => 'Vertuoza',
                    'fromDate' => '2024-06-06T08:18:00+02:00',
                    'toDate' => '2024-06-06T16:13:00+02:00',
                    'comment' => "VS-3847",
                    'minutes' => 475,
                ],
            ],
            [], //day off
            [], //saturday
            [], //sunday
        ];

        $mockedHttpClient
            ->method('request')
            ->willReturnOnConsecutiveCalls(...array_map(
                fn(array $i): Response => new Response(body: json_encode($i)),
                $workedHoursDataMap,
            ));

        $retriever = new PolcodeLinkWorkedTimeRetriever($linkApiKey, $mockedHttpClient);

        $workingMonth = $retriever->retrieve(
            new \DateTimeImmutable('2024-06-01'),
            new \DateTimeImmutable('2024-06-09')
        );

        $this->assertSame("32:00", (string) $workingMonth->getExpectedWorkedTimeDuration());
        $this->assertSame("32:19", (string) $workingMonth->getTotalWorkedTimeDuration());
    }
}
