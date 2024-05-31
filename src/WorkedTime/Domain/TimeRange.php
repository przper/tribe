<?php

namespace Przper\Tribe\WorkedTime\Domain;

final readonly class TimeRange
{
    private function __construct(
        private Time $start,
        private Time $end,
    ) {}

    public static function create(Time $start, Time $end): self
    {
        $duration = new self($start, $end);
        $duration->guard();

        return $duration;
    }

    private function guard(): void
    {
        if ($this->start->isPast($this->end)) {
            throw new IncorrectDurationException("$this->end must be after $this->start");
        }
    }

    public function intersects(TimeRange $timeRange): bool
    {
        if ($this->end->isPast($timeRange->start)) {
            return true;
        }

        if ($this->start->isPast($timeRange->end)) {
            return true;
        }

        return false;
    }
}
