<?php

namespace Tests\Doubles\MotherObjects;

use Przper\Tribe\FoodRecipes\Domain\Amount;
use Przper\Tribe\FoodRecipes\Domain\Ingredient;
use Przper\Tribe\FoodRecipes\Domain\Name;
use Przper\Tribe\FoodRecipes\Domain\Quantity;
use Przper\Tribe\FoodRecipes\Domain\Unit;

class IngredientMother
{
    private Ingredient $ingredient;

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

    private function getRandomArrayItem(array $list): mixed
    {
        return $list[array_rand($list)];
    }
}
