<?php

namespace Tests\Doubles\MotherObjects;

use Przper\Tribe\FoodRecipes\Domain\Amount;
use Przper\Tribe\FoodRecipes\Domain\Quantity;
use Przper\Tribe\FoodRecipes\Domain\Unit;

class AmountMother
{
    private Amount $amount;

    /** @var string[] $units */
    private array $units = [
        'kilogram',
        'can',
        'liter',
    ];

    private function __construct()
    {
        $this->amount = Amount::create(
            Quantity::fromFloat(1.0),
            Unit::fromString($this->getRandomArrayItem($this->units)),
        );
    }

    public static function new(): self
    {
        return new self();
    }

    public function build(): Amount
    {
        return clone $this->amount;
    }

    public function kilogram(float $quantity = 1.0): self
    {
        $this->amount->setQuantity(Quantity::fromFloat($quantity));
        $this->amount->setUnit(Unit::fromString('kilogram'));
        return $this;
    }


    public function can(float $quantity = 1.0): self
    {
        $this->amount->setQuantity(Quantity::fromFloat($quantity));
        $this->amount->setUnit(Unit::fromString('can'));
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