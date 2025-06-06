<?php

namespace Przper\Tribe\FoodRecipes\Application\Query;

use Przper\Tribe\FoodRecipes\Application\Query\Result\Ingredient;
use Przper\Tribe\FoodRecipes\Application\Query\Result\Recipe;
use Przper\Tribe\FoodRecipes\Domain\Recipe as DomainRecipe;
use Przper\Tribe\FoodRecipes\Domain\RecipeId;
use Przper\Tribe\FoodRecipes\Domain\RecipeRepositoryInterface;
use Przper\Tribe\Shared\Domain\Uuid;

final readonly class GetRecipe
{
    public function __construct(
        private RecipeRepositoryInterface $recipeRepository,
    ) {}

    public function execute(string $recipeId): ?Recipe
    {
        $recipe = $this->recipeRepository->get(RecipeId::fromString($recipeId));

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

        return new Recipe(
            id: (string) $recipe->getId(),
            name: (string) $recipe->getName(),
            ingredients: $applicationIngredients,
        );
    }
}
