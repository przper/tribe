<?php

namespace Tests\Unit\WorkedTime\Domain;

use PHPUnit\Framework\TestCase;
use Przper\Tribe\WorkedTime\Domain\IncorrectDurationException;
use Przper\Tribe\WorkedTime\Domain\Timestamp;
use Przper\Tribe\WorkedTime\Domain\Duration;

class DurationTest extends TestCase
{
    public function test_it_can_be_created_when_data_is_valid(): void
    {
        $start = Timestamp::fromDateTimeInterface(new \DateTime('today 08:00'));
        $end = Timestamp::fromDateTimeInterface(new \DateTime('today 16:00'));

        $duration = Duration::create($start, $end);

        $this->assertInstanceOf(Duration::class, $duration);
    }

    public function test_it_throws_IncorrectDurationException(): void
    {
        $start = Timestamp::fromDateTimeInterface(new \DateTime('today 08:00'));
        $end = Timestamp::fromDateTimeInterface(new \DateTime('today 16:00'));

        $this->expectException(IncorrectDurationException::class);

        Duration::create($end, $start);
    }
}
