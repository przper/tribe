<?php

namespace Tests\Doubles\MotherObjects\FoodRecipes;

use Przper\Tribe\FoodRecipes\Domain\Ingredient;
use Przper\Tribe\Shared\Domain\Amount;
use Przper\Tribe\Shared\Domain\Name;
use Przper\Tribe\Shared\Domain\Quantity;
use Przper\Tribe\Shared\Domain\Unit;

class IngredientMother
{
    private Ingredient $ingredient;

    /** @var string[] $names */
    private array $names = [
        'Meat',
        'Cheese',
        'Milk',
        'Tomato',
    ];

    /** @var string[] $units */
    private array $units = [
        'kilogram',
        'can',
        'liter',
    ];

    private function __construct()
    {
        $this->ingredient = Ingredient::create(
            Name::fromString($this->getRandomArrayItem($this->names)),
            Amount::create(
                Quantity::fromFloat(1.0),
                Unit::fromString($this->getRandomArrayItem($this->units)),
            ),
        );
    }

    public static function new(): self
    {
        return new self();
    }

    public function build(): Ingredient
    {
        return clone $this->ingredient;
    }

    public function kilogramsOfMeat(float $quantity = 1.0): self
    {
        $this->ingredient = Ingredient::create(
            Name::fromString('Meat'),
            Amount::create(Quantity::fromFloat($quantity), Unit::fromString('kilogram')),
        );

        return $this;
    }

    public function cansOfTomatoes(float $quantity = 1.0): self
    {
        $this->ingredient = Ingredient::create(
            Name::fromString('Tomatoes'),
            Amount::create(Quantity::fromFloat($quantity), Unit::fromString('can')),
        );

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
