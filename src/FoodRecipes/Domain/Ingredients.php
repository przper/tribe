<?php

declare(strict_types=1);

namespace Przper\Tribe\FoodRecipes\Domain;

use Przper\Tribe\Shared\Domain\Collection;

/**
 * @extends Collection<Ingredient>
 */
class Ingredients extends Collection
{
    /** @var Ingredient[] */
    private array $ingredients = [];

    /** @return Ingredient[] */
    public function getAll(): array
    {
        return $this->ingredients;
    }

    public function add(Ingredient $ingredient): void
    {
        if (!$this->contains($ingredient)) {
            $this->ingredients[] = $ingredient;
        } else {
            foreach ($this->ingredients as $i => $storedIngredient) {
                if ($storedIngredient->isTheSame($ingredient)) {
                    $this->ingredients[$i]->addAmount($ingredient->getAmount());
                }
            }
        }
    }

    public function set(Ingredient $ingredient): void
    {
        if (!$this->contains($ingredient)) {
            $this->ingredients[] = $ingredient;
        } else {
            foreach ($this->ingredients as $i => $storedIngredient) {
                if ($storedIngredient->isTheSame($ingredient)) {
                    $this->ingredients[$i] = $ingredient;
                }
            }
        }
    }

    public function contains(Ingredient $ingredient): bool
    {
        foreach ($this->ingredients as $i) {
            if ($i->isTheSame($ingredient)) {
                return true;
            }
        }

        return false;
    }

    protected function getItems(): array
    {
        return $this->ingredients;
    }
}
