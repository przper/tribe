<?php

namespace Przper\Tribe\Tests\Unit\WorkedTime\Unit\Domain;

use PHPUnit\Framework\TestCase;
use Przper\Tribe\WorkedTime\Domain\Date;
use Przper\Tribe\WorkedTime\Domain\Duration;
use Przper\Tribe\WorkedTime\Domain\WorkingDay;
use Przper\Tribe\WorkedTime\Domain\Time;
use Przper\Tribe\WorkedTime\Domain\WorkingMonth;
use Przper\Tribe\WorkedTime\Domain\WorkingMonthCreated;

class WorkingMonthTest extends TestCase
{
    public function test_it_can_be_created_from_valid_data(): void
    {
        $monthName = 'January';
        $workingDays = [
            WorkingDay::create(
                Date::fromString('2000-01-01'),
                [
                    Duration::create(
                        Time::fromString('07:00'),
                        Time::fromString('10:00'),
                    ),
                    Duration::create(
                        Time::fromString('14:00'),
                        Time::fromString('19:00'),
                    ),
                ],
            ),
            WorkingDay::create(
                Date::fromString('2000-01-02'),
                [
                    Duration::create(
                        Time::fromString('07:00'),
                        Time::fromString('15:00'),
                    ),
                ],
            ),
        ];

        $workingMonth = WorkingMonth::create($monthName, $workingDays);

        $events = $workingMonth->pullEvents();

        $this->assertInstanceOf(WorkingMonth::class, $workingMonth);
        $this->assertSame('January', $workingMonth->getMonthName());
        $this->assertEquals($workingDays, $workingMonth->getWorkingDays());
        $this->assertCount(1, $events);
        $this->assertInstanceOf(WorkingMonthCreated::class, $events[0]);
    }
}
