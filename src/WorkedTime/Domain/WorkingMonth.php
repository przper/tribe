<?php

namespace Przper\Tribe\WorkedTime\Domain;

use Przper\Tribe\Shared\Domain\AggregateRoot;

final class WorkingMonth extends AggregateRoot
{
    /**
     * @var array<string, WorkingDay> $workingDays
     */
    private array $workingDays = [];

    private function __construct(
        private Month $month,
    ) {}

    public static function create(Month $month): self
    {
        $workingMonth = new self($month);

        $workingMonth->raise(new WorkingMonthCreated($month->value, $month->name, '1'));

        return $workingMonth;
    }

    public function getMonth(): Month
    {
        return $this->month;
    }

    public function add(WorkingDay $workingDay): void
    {
        if (array_key_exists((string) $workingDay->getDate(), $this->workingDays)) {
            throw new WorkingDayAlreadyRegisteredException();
        }

        $this->workingDays[(string) $workingDay->getDate()] = $workingDay;
    }

    /**
     * @return WorkingDay[]
     */
    public function getWorkingDays(): array
    {
        return $this->workingDays;
    }

    public function getTotalWorkedTimeDuration(): TimeDuration
    {
        $duration = TimeDuration::create();

        foreach ($this->workingDays as $workedDay) {
            $duration->add($workedDay->getWorkedTimeDuration());
        }

        return $duration;
    }

    /**
     * Get the expected WorkedTimeDuration at the current moment.
     *
     * Example: If worked 2 days (9h + 8h) so far, this method will return
     * "16:00".
     *
     * @return TimeDuration
     */
    public function getExpectedWorkedTimeDuration(): TimeDuration
    {
        $expectedWorkedTime = TimeDuration::create();

        foreach ($this->workingDays as $workedDay) {
            $expectedWorkedTime->add(TimeDuration::create(hours: 8));
        }

        return $expectedWorkedTime;
    }
}
