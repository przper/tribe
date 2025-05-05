<?php

namespace Tests\Doubles\MotherObjects;

use Przper\Tribe\Shared\Domain\Amount;
use Przper\Tribe\Shared\Domain\Quantity;
use Przper\Tribe\Shared\Domain\Unit;

class AmountMother
{
    private Quantity $quantity;
    private Unit $unit;

    /** @var string[] $units */
    private array $units = [
        'kilogram',
        'can',
        'liter',
    ];

    private function __construct()
    {
        $this->quantity = Quantity::fromFloat(1.0);
        $this->unit = Unit::fromString($this->getRandomArrayItem($this->units));
    }

    public static function new(): self
    {
        return new self();
    }

    public function build(): Amount
    {
        return Amount::create($this->quantity, $this->unit);
    }

    public function kilogram(float $quantity = 1.0): self
    {
        $this->quantity = Quantity::fromFloat($quantity);
        $this->unit = Unit::fromString('kilogram');
        return $this;
    }

    public function can(float $quantity = 1.0): self
    {
        $this->quantity = Quantity::fromFloat($quantity);
        $this->unit = Unit::fromString('can');
        return $this;
    }

    public function liter(float $quantity = 1.0): self
    {
        $this->quantity = Quantity::fromFloat($quantity);
        $this->unit = Unit::fromString('liter');
        return $this;
    }

    public function quantity(float $quantity): self
    {
        $this->quantity = Quantity::fromFloat($quantity);
        return $this;
    }

    public function unit(string $unit): self
    {
        $this->unit = Unit::fromString($unit);
        return $this;
    }

    /**
     * @template T
     * @param list<T> $list
     * @return T
     */
    private function getRandomArrayItem(array $list): mixed
    {
        return $list[array_rand($list)];
    }
}
