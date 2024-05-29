<?php

namespace Tests\Unit\WorkedTime\Domain;

use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\TestCase;
use Przper\Tribe\WorkedTime\Domain\Time;

class TimeTest extends TestCase
{
    public function test_it_can_be_created_when_data_is_valid(): void
    {
        $time = Time::fromString('11:11');

        $this->assertSame('11:11', (string) $time);
    }

    #[DataProvider('isPastTimeProvider')]
    public function test_it_checks_if_it_is_past_other_time(string $before, string $after, bool $expected): void
    {
        $time = Time::fromString($before);
        $toCheckTime = Time::fromString($after);

        $this->assertEquals($expected, $time->isPast($toCheckTime));
    }

    public static function isPastTimeProvider(): iterable
    {
        yield ['08:00', '10:00', false];
        yield ['11:00', '10:00', true];
        yield ['11:00', '11:00', false];
    }
}
