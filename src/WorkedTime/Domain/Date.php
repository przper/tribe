<?php

namespace Przper\Tribe\WorkedTime\Domain;

final readonly class Date
{
    final const DATE_FORMAT = 'Y-m-d';

    private function __construct(
        private \DateTimeImmutable $date,
    ) {
    }

    public static function fromString(string $date): self
    {
        $date = \DateTimeImmutable::createFromFormat('!Y-m-d', $date);

        if ($date === false) {
            throw new InvalidDateException();
        }

        return new self($date);
    }

    public function __toString(): string
    {
        return $this->date->format(self::DATE_FORMAT);
    }
}
