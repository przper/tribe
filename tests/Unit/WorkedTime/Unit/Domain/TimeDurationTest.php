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

    #[Test]
    public function it_can_add_time(): void
    {
        $duration = TimeDuration::create();

        $this->assertSame("00:00", (string) $duration);

        $duration->add(TimeDuration::create(hours: 1));

        $this->assertSame("01:00", (string) $duration);

        $duration->add(TimeDuration::create(minutes: 47));

        $this->assertSame("01:47", (string) $duration);

        $duration->add(TimeDuration::create(minutes: 47));

        $this->assertSame("02:34", (string) $duration);
    }

    #[Test]
    public function it_checks_if_it_is_greater_than(): void
    {
        $duration = TimeDuration::create(hours: 5);

        $this->assertTrue($duration->isGreaterThan(TimeDuration::create(4)));
        $this->assertFalse($duration->isGreaterThan(TimeDuration::create(5)));
        $this->assertFalse($duration->isGreaterThan(TimeDuration::create(6)));
    }

    #[Test]
    public function it_calculates_difference(): void
    {
        $duration = TimeDuration::create(hours: 5);

        $this->assertSame("00:00", (string) $duration->difference(TimeDuration::create(5)));
        $this->assertSame("00:15", (string) $duration->difference(TimeDuration::create(5, 15)));
        $this->assertSame("00:15", (string) $duration->difference(TimeDuration::create(4, 45)));
    }
}
