<?php

namespace Przper\Tribe\Tests\Unit\WorkedTime\Unit\Domain;

use PHPUnit\Framework\Attributes\Test;
use PHPUnit\Framework\TestCase;
use Przper\Tribe\WorkedTime\Domain\Date;
use Przper\Tribe\WorkedTime\Domain\Month;
use Przper\Tribe\WorkedTime\Domain\WorkingDay;
use Przper\Tribe\WorkedTime\Domain\WorkingDayAlreadyRegisteredException;
use Przper\Tribe\WorkedTime\Domain\WorkingMonth;
use Przper\Tribe\WorkedTime\Domain\WorkingMonthCreated;

class WorkingMonthTest extends TestCase
{
    public function test_it_can_be_created_from_valid_data(): void
    {
        $workingMonth = WorkingMonth::create(Month::January);

        $events = $workingMonth->pullEvents();

        $this->assertInstanceOf(WorkingMonth::class, $workingMonth);
        $this->assertSame('January', $workingMonth->getMonth()->value);
        $this->assertCount(1, $events);
        $this->assertInstanceOf(WorkingMonthCreated::class, $events[0]);
    }

    #[Test]
    public function it_add_working_days(): void
    {
        $workingMonth = WorkingMonth::create(Month::August);
        $workingDay = WorkingDay::create(Date::fromString('2000-08-01'));
        $workingMonth->add($workingDay);

        $this->assertCount(1, $workingMonth->getWorkingDays());
        $this->assertEquals($workingDay, $workingMonth->getWorkingDays()['2000-08-01']);

        $this->expectException(WorkingDayAlreadyRegisteredException::class);
        $workingMonth->add($workingDay);
    }
}
