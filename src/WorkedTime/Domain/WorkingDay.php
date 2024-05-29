<?php

namespace Przper\Tribe\WorkedTime\Domain;

use Przper\Tribe\Shared\Domain\AggregateRoot;

class WorkingDay
{
    /**
     * @param Duration[] $workedTimes
     */
    private function __construct(
        private Date $date,
        private array $workedTimes,
    ) {}

    /**
     * @param Duration[] $workedTimes
     */
    public static function create(Date $date, array $workedTimes): self
    {
        $workedDay = new self($date, $workedTimes);

        return $workedDay;
    }

    public function getDate(): Date
    {
        return $this->date;
    }

    /**
     * @return Duration[]
     */
    public function getWorkedTimes(): array
    {
        return $this->workedTimes;
    }
}
