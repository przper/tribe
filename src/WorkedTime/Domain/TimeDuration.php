<?php

namespace Przper\Tribe\WorkedTime\Domain;

final class TimeDuration
{
    private function __construct(
        private int $minutes,
    ) {}

    public static function create(int $hours = 0, int $minutes = 0): self
    {
        if ($hours < 0) {
            throw new \InvalidArgumentException('Hours must be greater than 0');
        }

        if ($minutes < 0) {
            throw new \InvalidArgumentException('Minutes must be greater than 0');
        }

        $totalMinutes = $hours * 60 + $minutes;

        return new self($totalMinutes);
    }

    public function add(TimeDuration $newDuration): void
    {
        $this->minutes += $newDuration->minutes;
    }

    public function isGreaterThan(TimeDuration $timeDuration): bool
    {
        return $timeDuration->minutes > $this->minutes;
    }

    public function __toString(): string
    {
        $hours = floor($this->minutes / 60);
        $hours = str_pad((string) $hours, 2, "0", STR_PAD_LEFT);

        $minutes = $this->minutes % 60;
        $minutes = str_pad((string) $minutes, 2, "0", STR_PAD_LEFT);

        return "$hours:$minutes";
    }
}
