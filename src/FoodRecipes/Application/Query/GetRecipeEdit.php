<?php

namespace Przper\Tribe\FoodRecipes\Application\Query;

use Przper\Tribe\FoodRecipes\Application\Query\Result\Ingredient;
use Przper\Tribe\FoodRecipes\Application\Query\Result\RecipeEdit;
use Przper\Tribe\FoodRecipes\Application\Query\Result\RecipeEdit as ApplicationRecipe;
use Przper\Tribe\FoodRecipes\Domain\Recipe as DomainRecipe;
use Przper\Tribe\FoodRecipes\Domain\RecipeId;
use Przper\Tribe\FoodRecipes\Domain\RecipeRepositoryInterface;

final readonly class GetRecipeEdit
{
    public function __construct(
        private RecipeRepositoryInterface $recipeRepository,
    ) {}

    public function execute(RecipeId $id): ?RecipeEdit
    {
        $recipe = $this->recipeRepository->get($id);

        if (!$recipe instanceof DomainRecipe) {
            return null;
        }

        $applicationIngredients = [];

        foreach ($recipe->getIngredients() as $ingredient) {
            $quantityAsString = (string) $ingredient->getAmount()->getQuantity();

            $applicationIngredients[] = new Ingredient(
                name: (string) $ingredient->getName(),
                quantity: (float) $quantityAsString,
                unit: (string) $ingredient->getAmount()->getUnit(),
            );
        }

        return new ApplicationRecipe(
            id: (string) $recipe->getId(),
            name: (string) $recipe->getName(),
            ingredients: $applicationIngredients,
        );
    }
}
