<?php

declare(strict_types=1);

namespace Przper\Tribe\FoodRecipies\Domain;

class Ingredients
{
    /** @var Ingredient[] */
    public array $ingredients = [];

    public function add(Ingredient $ingredient): void
    {
        if (!$this->contains($ingredient)) {
            $this->ingredients[] = $ingredient;
        }
    }

    public function contains(Ingredient $ingredient): bool
    {
        foreach ($this->ingredients as $i) {
            if ($i->name === $ingredient->name) {
                return true;
            }
        }

        return false;
    }
}
