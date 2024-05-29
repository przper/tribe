<?php

namespace Przper\Tribe\WorkedTime\Domain;

class Duration
{
    private function __construct(
        private Timestamp $start,
        private Timestamp $end,
    ) {}

    public static function create(Timestamp $start, Timestamp $end): self
    {
        $duration = new self($start, $end);
        $duration->guard();

        return $duration;
    }

    private function guard(): void
    {
        if ($this->start->isPastTimestamp($this->end)) {
            throw new IncorrectDurationException("$this->end must be after $this->start");
        }
    }
}
