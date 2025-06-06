<?php

declare(strict_types=1);

namespace Przper\Tribe\FoodRecipes\Domain;

use Przper\Tribe\Shared\Domain\Amount;
use Przper\Tribe\Shared\Domain\Name;
use Przper\Tribe\Shared\Domain\NotMatchingAmountUnitException;

final class Ingredient
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
     * Check if an Ingredient is the same as other Ingredient. This method does not evaluate the Amount
     */
    public function isTheSame(self $ingredient): bool
    {
        return $this->name->is($ingredient->name);
    }

    /**
     * @throws NotMatchingAmountUnitException
     */
    public function addAmount(Amount $amount): void
    {
        $this->amount->add($amount);
    }
}
