<?php

declare(strict_types=1);

namespace Przper\Tribe\FoodRecipes\Domain;

class Ingredient
{
    private function __construct(
        private readonly Name $name,
        private Amount $amount,
    ) {}

    public static function create(Name $name, Amount $amount): self
    {
        $ingredient = new self($name, $amount);
        $ingredient->guard();

        return $ingredient;
    }

    public function getAmount(): Amount
    {
        return $this->amount;
    }

    public function setAmount(Amount $amount): self
    {
        $this->amount = $amount;

        return $this;
    }

    public function getName(): Name
    {
        return $this->name;
    }

    private function guard(): void {}

    /**
     * Check if an Ingredient is the same as other Ingredient
     */
    public function isTheSame(self $ingredient): bool
    {
        return $this->name->isEqual($ingredient->name);
    }
}
