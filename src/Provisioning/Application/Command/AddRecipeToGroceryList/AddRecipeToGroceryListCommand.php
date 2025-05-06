<?php

namespace Przper\Tribe\Provisioning\Application\Command\AddRecipeToGroceryList;

final readonly class AddRecipeToGroceryListCommand
{
    public function __construct(
        public string $groceryListId,
        public string $recipeId,
    ) {}
}
