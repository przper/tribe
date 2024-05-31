<?php

namespace Przper\Tribe\Tests\Unit\WorkedTime\Unit\Domain;

use PHPUnit\Framework\Attributes\Test;
use PHPUnit\Framework\TestCase;
use Przper\Tribe\WorkedTime\Domain\TimeDuration;

class TimeDurationTest extends TestCase
{
    #[Test]
    public function it_can_be_created_from_valid_data(): void
    {
        $duration = TimeDuration::create(hours: 1, minutes: 30);

        $this->assertSame("01:30", (string) $duration);
    }

    #[Test]
    public function it_throws_exception_for_negative_hours(): void
    {
        $this->expectException(\InvalidArgumentException::class);

        TimeDuration::create(hours: -1);
    }

    #[Test]
    public function it_throws_exception_for_negative_minutes(): void
    {
        $this->expectException(\InvalidArgumentException::class);

        TimeDuration::create(minutes: -1);
    }

    #[Test]
    public function it_overflows_minutes(): void
    {
        $duration = TimeDuration::create(hours: 3, minutes: 227);

        $this->assertSame("06:47", (string) $duration);
    }
}