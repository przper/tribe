<?php

declare(strict_types=1);

namespace Przper\Tribe\FoodRecipes\Domain;

class Ingredient
{
    public Amount $amount;

    public function __construct(
        private Name $name,
    ) {}

    public function getName(): Name
    {
        return $this->name;
    }

    public function isTheSameIngredient(Ingredient $ingredient): bool
    {
        return $this->name->value === $ingredient->name->value;
    }
}
