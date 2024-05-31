<?php

namespace Tests\Unit\WorkedTime\Domain;

use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\Attributes\Test;
use PHPUnit\Framework\TestCase;
use Przper\Tribe\WorkedTime\Domain\IncorrectDurationException;
use Przper\Tribe\WorkedTime\Domain\Time;
use Przper\Tribe\WorkedTime\Domain\TimeRange;

class TimeRangeTest extends TestCase
{
    public function test_it_can_be_created_when_data_is_valid(): void
    {
        $start = Time::fromString('08:00');
        $end = Time::fromString('16:00');

        $timeRange = TimeRange::create($start, $end);

        $this->assertInstanceOf(TimeRange::class, $timeRange);
    }

    public function test_duration_with_zero_length_can_be_created(): void
    {
        $start = Time::fromString('08:00');
        $end = Time::fromString('08:00');

        $timeRange = TimeRange::create($start, $end);

        $this->assertInstanceOf(TimeRange::class, $timeRange);
    }

    public function test_it_throws_IncorrectDurationException(): void
    {
        $this->expectException(IncorrectDurationException::class);

        TimeRange::create(
            Time::fromString('16:00'),
            Time::fromString('08:00'),
        );
    }

    #[DataProvider('intersect')]
    public function test_it_intersects_another_TimeRange(TimeRange $a, TimeRange $b, bool $expected): void
    {
        $this->assertEquals($a->intersects($b), $expected);
    }

    public static function intersect(): \Generator
    {
        yield [
            TimeRange::create(Time::fromString('08:00'), Time::fromString('10:00')),
            TimeRange::create(Time::fromString('11:00'), Time::fromString('13:00')),
            false,
        ];
        yield [
            TimeRange::create(Time::fromString('08:00'), Time::fromString('10:00')),
            TimeRange::create(Time::fromString('09:00'), Time::fromString('13:00')),
            true,
        ];
        yield [
            TimeRange::create(Time::fromString('08:00'), Time::fromString('10:00')),
            TimeRange::create(Time::fromString('07:00'), Time::fromString('09:00')),
            true,
        ];
    }

    #[Test]
    #[DataProvider('durations')]
    public function it_calculates_duration(Time $a, Time $b, string $expectedDuration): void
    {
        $timeRange = TimeRange::create($a, $b);

        $this->assertSame($expectedDuration, (string) $timeRange->getDuration());
    }

    public static function durations(): \Generator
    {
        yield [Time::fromString('08:00'), Time::fromString('10:00'), '02:00'];
        yield [Time::fromString('06:47'), Time::fromString('07:00'), '00:13'];
        yield [Time::fromString('07:00'), Time::fromString('07:13'), '00:13'];
        yield [Time::fromString('06:47'), Time::fromString('07:13'), '00:26'];
        yield [Time::fromString('10:00'), Time::fromString('10:00'), '00:00'];
    }
}
