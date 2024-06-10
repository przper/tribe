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
            array(0) {
    }
array(0) {
    }
array(1) {
        [0]=>
  array(7) {
            ["id"]=>
    int(120095)
    ["categoryId"]=>
    int(434)
    ["categoryName"]=>
    string(8) "Vertuoza"
            ["fromDate"]=>
    string(25) "2024-06-03T07:40:00+02:00"
            ["toDate"]=>
    string(25) "2024-06-03T15:54:00+02:00"
            ["comment"]=>
    string(19) "VS-3845, 2716, 3846"
            ["minutes"]=>
    int(494)
  }
}
array(2) {
        [0]=>
  array(7) {
            ["id"]=>
    int(120212)
    ["categoryId"]=>
    int(434)
    ["categoryName"]=>
    string(8) "Vertuoza"
            ["fromDate"]=>
    string(25) "2024-06-04T07:46:00+02:00"
            ["toDate"]=>
    string(25) "2024-06-04T12:03:00+02:00"
            ["comment"]=>
    string(7) "VS-3847"
            ["minutes"]=>
    int(257)
  }
  [1]=>
  array(7) {
            ["id"]=>
    int(120213)
    ["categoryId"]=>
    int(434)
    ["categoryName"]=>
    string(8) "Vertuoza"
            ["fromDate"]=>
    string(25) "2024-06-04T13:10:00+02:00"
            ["toDate"]=>
    string(25) "2024-06-04T16:35:00+02:00"
            ["comment"]=>
    string(7) "VS-3847"
            ["minutes"]=>
    int(205)
  }
}
array(1) {
        [0]=>
            [
                'id' => 120351,
                'categoryId' => 434,
                'categoryName' => 'Vertuoza',
                'fromDate' => '2024-06-05T07:40:00+02:00',
                'toDate' => '2024-06-05T16:08:00+02:00',
                'comment' => "VS-3847",
                'minutes' => 508,
            ]
}
array(1) {
        [0]=>
  array(7) {
            ["id"]=>
    int(120758)
    ["categoryId"]=>
    int(434)
        "fromDate":"2024-06-06T08:18:00+02:00"
        "toDate":"2024-06-06T16:13:00+02:00"
            ["comment"]=>
    string(7) "VS-3847"
            ["minutes"]=>
    int(475)
  }
}
    [],
    [],
    [],
    [],

        ];

        $mockedHttpClient
            ->method('request')
            ->willReturnOnConsecutiveCalls(
                new Response(body: json_encode($workedHoursDataMap['2000-01-01'])),
                new Response(body: json_encode($workedHoursDataMap['2000-01-02'])),
                new Response(body: json_encode($workedHoursDataMap['2000-01-03'])),
            );

        $retriever = new PolcodeLinkWorkedTimeRetriever($linkApiKey, $mockedHttpClient);

        $workingMonth = $retriever->retrieve(
            new \DateTimeImmutable('2024-06-01'),
            new \DateTimeImmutable('2024-06-10')
        );

        $this->assertSame("24:00", (string) $workingMonth->getExpectedWorkedTimeDuration());
        $this->assertSame("23:06", (string) $workingMonth->getTotalWorkedTimeDuration());
    }
}

array(0) {
}
array(0) {
}
array(1) {
    [0]=>
  array(7) {
        ["id"]=>
    int(120095)
    ["categoryId"]=>
    int(434)
    ["categoryName"]=>
    string(8) "Vertuoza"
        ["fromDate"]=>
    string(25) "2024-06-03T07:40:00+02:00"
        ["toDate"]=>
    string(25) "2024-06-03T15:54:00+02:00"
        ["comment"]=>
    string(19) "VS-3845, 2716, 3846"
        ["minutes"]=>
    int(494)
  }
}
array(2) {
    [0]=>
  array(7) {
        ["id"]=>
    int(120212)
    ["categoryId"]=>
    int(434)
    ["categoryName"]=>
    string(8) "Vertuoza"
        ["fromDate"]=>
    string(25) "2024-06-04T07:46:00+02:00"
        ["toDate"]=>
    string(25) "2024-06-04T12:03:00+02:00"
        ["comment"]=>
    string(7) "VS-3847"
        ["minutes"]=>
    int(257)
  }
  [1]=>
  array(7) {
        ["id"]=>
    int(120213)
    ["categoryId"]=>
    int(434)
    ["categoryName"]=>
    string(8) "Vertuoza"
        ["fromDate"]=>
    string(25) "2024-06-04T13:10:00+02:00"
        ["toDate"]=>
    string(25) "2024-06-04T16:35:00+02:00"
        ["comment"]=>
    string(7) "VS-3847"
        ["minutes"]=>
    int(205)
  }
}
array(1) {
    [0]=>
  array(7) {
        ["id"]=>
    int(120351)
    ["categoryId"]=>
    int(434)
    ["categoryName"]=>
    string(8) "Vertuoza"
        ["fromDate"]=>
    string(25) "2024-06-05T07:40:00+02:00"
        ["toDate"]=>
    string(25) "2024-06-05T16:08:00+02:00"
        ["comment"]=>
    string(7) "VS-3847"
        ["minutes"]=>
    int(508)
  }
}
array(1) {
    [0]=>
  array(7) {
        ["id"]=>
    int(120758)
    ["categoryId"]=>
    int(434)
    string(25) "2024-06-06T16:13:00+02:00"
        ["comment"]=>
    string(7) "VS-3847"
        ["minutes"]=>
    int(475)
  }
}
array(0) {
}
array(0) {
}
array(0) {
}
array(0) {
}
