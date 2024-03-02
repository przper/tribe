<?php

declare(strict_types=1);

namespace Przper\Tribe\FoodRecipes\Domain;

class Ingredient
{
    private Amount $amount;

    private function __construct(
        private readonly Name $name,
    ) {}

    public static function create(Name $name): self
    {
        $ingredient = new self($name);
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

    public function equals(self $ingredient): bool
    {
        return $this->name->equal($ingredient->name);
    }
}
