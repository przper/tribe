<?php

namespace Przper\Tribe\FoodRecipes\Application\Command\CreateRecipe;

final readonly class CreateRecipeCommand
{
    public function __construct(
        public string $name,
    ) {
    }
}