<?php

namespace Przper\Tribe\Tests\Unit\WorkedTime\Unit\Domain;

use PHPUnit\Framework\TestCase;
use Przper\Tribe\WorkedTime\Domain\WorkingDay;
use Przper\Tribe\WorkedTime\Domain\Date;
use Przper\Tribe\WorkedTime\Domain\Duration;
use Przper\Tribe\WorkedTime\Domain\Time;

class WorkingDayTest extends TestCase
{
    public function testCreateWorkingDateValidData(): void
    {
        $date = Date::fromString('2000-01-01');
        $workedHours = [
            Duration::create(
                Time::fromString('07:00'),
                Time::fromString('10:00'),
            ),
            Duration::create(
                Time::fromString('14:00'),
                Time::fromString('19:00'),
            ),
        ];

        $workingDay = WorkingDay::create($date, $workedHours);

        $this->assertInstanceOf(WorkingDay::class, $workingDay);
        $this->assertEquals($date, $workingDay->getDate());
        $this->assertEquals($workedHours, $workingDay->getWorkedTimes());
    }
}