<?php

namespace Przper\Tribe\FoodRecipes\Application\Command\UpdateRecipe;

final readonly class UpdateRecipeCommand
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