<?php

namespace Przper\Tribe\WorkedTime\Domain;

use Przper\Tribe\Shared\Domain\AggregateRoot;

final class WorkingMonth extends AggregateRoot
{
    /**
     * @param WorkingDay[] $workingDays
     */
    private function __construct(
        private string $monthName,
        private array $workingDays,
    ) {
    }

    /**
     * @param WorkingDay[] $workingDays
     */
    public static function create(string $monthName, array $workingDays): self
    {
        $month = new self($monthName, $workingDays);

        $month->raise(new WorkingMonthCreated($monthName, $monthName, '1'));

        return $month;
    }

    public function getMonthName(): string
    {
        return $this->monthName;
    }

    /**
     * @return WorkingDay[]
     */
    public function getWorkingDays(): array
    {
        return $this->workingDays;
    }
}