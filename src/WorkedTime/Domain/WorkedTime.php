<?php

namespace Przper\Tribe\WorkedTime\Domain;

class WorkedTime
{
    private function __construct(
        private Timestamp $start,
        private Timestamp $end,
    ) {
    }

    public static function create(Timestamp $start, Timestamp $end): self
    {
        $workedTime = new self($start, $end);
        $workedTime->guard();

        return $workedTime;
    }

    private function guard(): void
    {
        if ($this->start->isPastTimestamp($this->end)) {
            throw new IncorrectWorkedTimeException("$this->end must be after $this->start");
        }
    }
}