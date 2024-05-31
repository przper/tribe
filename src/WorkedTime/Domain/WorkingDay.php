<?php

namespace Przper\Tribe\WorkedTime\Domain;

final class WorkingDay
{
    /**
     * @var TimeRange[] $workedTimes
     */
    private array $workedTimes = [];

    private function __construct(
        private Date $date,
    ) {}

    public static function create(Date $date): self
    {
        $workedDay = new self($date);

        return $workedDay;
    }

    public function getDate(): Date
    {
        return $this->date;
    }

    public function add(TimeRange $timeRange): void
    {
        foreach ($this->workedTimes as $workedTime) {
            if ($workedTime->intersects($timeRange)) {
                throw new \InvalidArgumentException('New worked overlaps already registered worked time');
            }
        }
        $this->workedTimes[] = $timeRange;
    }

    public function getWorkedTimeDuration(): TimeDuration
    {
        $duration = TimeDuration::create();

        foreach ($this->workedTimes as $workedTime) {
            $duration->add($workedTime->getDuration());
        }

        return $duration;
    }

    /**
     * @return TimeRange[]
     */
    public function getWorkedTimes(): array
    {
        return $this->workedTimes;
    }
}
