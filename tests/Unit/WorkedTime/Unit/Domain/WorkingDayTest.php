<?php

namespace Przper\Tribe\Tests\Unit\WorkedTime\Unit\Domain;

use PHPUnit\Framework\Attributes\Test;
use PHPUnit\Framework\TestCase;
use Przper\Tribe\WorkedTime\Domain\WorkingDay;
use Przper\Tribe\WorkedTime\Domain\Date;
use Przper\Tribe\WorkedTime\Domain\TimeRange;
use Przper\Tribe\WorkedTime\Domain\Time;

class WorkingDayTest extends TestCase
{
    public function testCreateWorkingDateValidData(): void
    {
        $date = Date::fromString('2000-01-01');

        $workingDay = WorkingDay::create($date);

        $this->assertInstanceOf(WorkingDay::class, $workingDay);
        $this->assertEquals($date, $workingDay->getDate());
    }

    public function testItAddsWorkedTime(): void
    {
        $workedHours = [
            TimeRange::create(
                Time::fromString('07:00'),
                Time::fromString('10:00'),
            ),
            TimeRange::create(
                Time::fromString('14:00'),
                Time::fromString('19:00'),
            ),
        ];

        $workingDay = WorkingDay::create(Date::fromString('2000-01-01'));
        $workingDay->add($workedHours[0]);
        $workingDay->add($workedHours[1]);

        $this->assertEquals($workedHours, $workingDay->getWorkedTimes());
    }

    #[Test]
    public function it_calculates_worked_time(): void
    {
        $workingDay = WorkingDay::create(Date::fromString('2000-01-01'));
        $workingDay->add(TimeRange::create(
            Time::fromString('07:00'),
            Time::fromString('10:00'),
        ));
        $workingDay->add(TimeRange::create(
            Time::fromString('11:00'),
            Time::fromString('14:00'),
        ));

        $this->assertSame("06:00", (string) $workingDay->getWorkedTimeDuration());
    }

    #[Test]
    public function it_prevent_adding_overlapping_worked_time(): void
    {
        $workingDay = WorkingDay::create(Date::fromString('2000-01-01'));
        $workingDay->add(TimeRange::create(
            Time::fromString('08:00'),
            Time::fromString('16:00'),
        ));

        $this->expectException(\InvalidArgumentException::class);
        $workingDay->add(TimeRange::create(
            Time::fromString('11:00'),
            Time::fromString('14:00'),
        ));
    }
}
