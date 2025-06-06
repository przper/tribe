<?php

namespace Przper\Tribe\FoodRecipes\Application\Command\UpdateRecipe;

use Przper\Tribe\Shared\Application\Command\Sync\Command;

final readonly class UpdateRecipeCommand implements Command
{
    /**
     * @param list<array{name: string, quantity: float, unit: string}> $ingredients
     */
    public function __construct(
        public string $id,
        public string $name,
        public array $ingredients,
    ) {}
}
