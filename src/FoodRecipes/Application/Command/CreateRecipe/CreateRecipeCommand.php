<?php

namespace Przper\Tribe\FoodRecipes\Application\Command\CreateRecipe;

final readonly class CreateRecipeCommand
{
    /**
     * @param list<array{name: string, quantity: float, unit: string}> $ingredients
     */
    public function __construct(
        public string $name,
        public array $ingredients,
    ) {}
}
