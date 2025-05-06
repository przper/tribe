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

        return $this->start->isPast($timeRange->end);
    }

    public function getDuration(): TimeDuration
    {
        $endTotalMinutes = $this->end->getHours() * 60 + $this->end->getMinutes();
        $startTotalMinutes = $this->start->getHours() * 60 + $this->start->getMinutes();

        $minutes = $endTotalMinutes - $startTotalMinutes;

        return TimeDuration::create(minutes: $minutes);
    }
}
