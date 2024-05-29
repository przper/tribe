<?php

namespace Tests\Unit\WorkedTime\Domain;

use PHPUnit\Framework\TestCase;
use Przper\Tribe\WorkedTime\Domain\IncorrectWorkedTimeException;
use Przper\Tribe\WorkedTime\Domain\Timestamp;
use Przper\Tribe\WorkedTime\Domain\WorkedTime;

class WorkedTimeTest extends TestCase
{
    public function test_it_can_be_created_when_data_is_valid(): void
    {
        $start = Timestamp::fromDateTimeInterface(new \DateTime('today 08:00'));
        $end = Timestamp::fromDateTimeInterface(new \DateTime('today 16:00'));

        $workedTime = WorkedTime::create($start, $end);

        $this->assertInstanceOf(WorkedTime::class, $workedTime);
    }

    public function test_it_throws_IncorrectWorkedTimeException(): void
    {
        $start = Timestamp::fromDateTimeInterface(new \DateTime('today 08:00'));
        $end = Timestamp::fromDateTimeInterface(new \DateTime('today 16:00'));

        $this->expectException(IncorrectWorkedTimeException::class);

        WorkedTime::create($end, $start);
    }
}
