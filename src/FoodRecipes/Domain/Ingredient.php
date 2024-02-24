<?php

declare(strict_types=1);

namespace Przper\Tribe\FoodRecipes\Domain;

class Ingredient
{
    private Amount $amount;

    public function __construct(
        public readonly Name $name,
    ) {}

    public function getAmount(): Amount
    {
        return $this->amount;
    }

    public function setAmount(Amount $amount): self
    {
        $this->amount = $amount;

        return $this;
    }

    public function isTheSameIngredient(Ingredient $ingredient): bool
    {
        return $this->name->value === $ingredient->name->value;
    }
}
