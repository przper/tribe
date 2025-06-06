<?php

namespace Przper\Tribe\Provisioning\Application\Command\AddRecipeToGroceryList;

use Przper\Tribe\Shared\Application\Command\Sync\Command;

final readonly class AddRecipeToGroceryListCommand implements Command
{
    public function __construct(
        public string $groceryListId,
        public string $recipeId,
    ) {}
}
