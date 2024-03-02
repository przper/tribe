<?php

declare(strict_types=1);

namespace Przper\Tribe\FoodRecipes\Domain;

class Ingredients
{
    /** @var Ingredient[] */
    private array $ingredients = [];

    public function getAll(): array
    {
        return $this->ingredients;
    }

    public function add(Ingredient $ingredient): void
    {
        if (!$this->contains($ingredient)) {
            $this->ingredients[] = $ingredient;
        }
    }

    public function contains(Ingredient $ingredient): bool
    {
        foreach ($this->ingredients as $i) {
            if ($i->equals($ingredient)) {
                return true;
            }
        }

        return false;
    }
}
