<?php

namespace Przper\Tribe\Provisioning\Application\Query;

use Przper\Tribe\Provisioning\Application\Query\Result\GroceryList;
use Przper\Tribe\Provisioning\Application\Query\Result\GroceryListItem;
use Przper\Tribe\Provisioning\Infrastructure\FoodRecipes\RecipeTranslator;
use Symfony\Component\DependencyInjection\Attribute\Autoconfigure;

#[Autoconfigure(public: true)]
final readonly class GetGroceryListFromFoodRecipe
{
    public function __construct(
        private RecipeTranslator $recipeTranslator,
    ) {}

    public function execute(string $recipeId): ?GroceryList
    {
        $recipe = $this->recipeTranslator->translate($recipeId);

        if ($recipe === null) {
            return null;
        }

        $groceryListItems = [];
        foreach ($recipe->ingredients as $ingredient) {
            $groceryListItems[] = new GroceryListItem(
                name: $ingredient->name,
                quantity: $ingredient->quantity,
                unit: $ingredient->unit,
            );
        }

        return new GroceryList($groceryListItems);
    }
}
