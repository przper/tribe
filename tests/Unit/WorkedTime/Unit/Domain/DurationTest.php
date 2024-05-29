<?php

namespace Tests\Unit\WorkedTime\Domain;

use PHPUnit\Framework\TestCase;
use Przper\Tribe\WorkedTime\Domain\IncorrectDurationException;
use Przper\Tribe\WorkedTime\Domain\Time;
use Przper\Tribe\WorkedTime\Domain\Duration;

class DurationTest extends TestCase
{
    public function test_it_can_be_created_when_data_is_valid(): void
    {
        $start = Time::fromString('08:00');
        $end = Time::fromString('16:00');

        $duration = Duration::create($start, $end);

        $this->assertInstanceOf(Duration::class, $duration);
    }

    public function test_it_throws_IncorrectDurationException(): void
    {
        $this->expectException(IncorrectDurationException::class);

        Duration::create(
            Time::fromString('16:00'),
            Time::fromString('08:00'),
        );
    }
}
