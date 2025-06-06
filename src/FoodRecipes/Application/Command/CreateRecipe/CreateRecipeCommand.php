<?php

namespace Przper\Tribe\FoodRecipes\Application\Command\CreateRecipe;

use Przper\Tribe\Shared\Application\Command\Sync\Command;

final readonly class CreateRecipeCommand implements Command
{
    /**
     * @param list<array{name: string, quantity: float, unit: string}> $ingredients
     */
    public function __construct(
        public string $name,
        public array $ingredients,
    ) {}
}
