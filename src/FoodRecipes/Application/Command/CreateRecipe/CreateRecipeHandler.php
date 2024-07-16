<?php

namespace Przper\Tribe\FoodRecipes\Application\Command\CreateRecipe;

use Przper\Tribe\FoodRecipes\Application\Projection\RecipeProjector;
use Przper\Tribe\FoodRecipes\Domain\Amount;
use Przper\Tribe\FoodRecipes\Domain\Ingredient;
use Przper\Tribe\FoodRecipes\Domain\Ingredients;
use Przper\Tribe\FoodRecipes\Domain\Name;
use Przper\Tribe\FoodRecipes\Domain\Quantity;
use Przper\Tribe\FoodRecipes\Domain\Recipe;
use Przper\Tribe\FoodRecipes\Domain\RecipeId;
use Przper\Tribe\FoodRecipes\Domain\RecipeRepositoryInterface;
use Przper\Tribe\FoodRecipes\Domain\Unit;

final class CreateRecipeHandler
{
    public function __construct(
        private RecipeRepositoryInterface $repository,
        private RecipeProjector $recipeProjector,
    ) {}

    public function __invoke(CreateRecipeCommand $command): void
    {
        $recipe = Recipe::create(
            new RecipeId((string) rand(1000000, 9999999)),
            Name::fromString($command->name),
        );

        $ingredients = new Ingredients();
        foreach ($command->ingredients as $ingredientData) {
            $ingredient = Ingredient::create(
                Name::fromString($ingredientData['name']),
                Amount::create(
                    Quantity::fromFloat($ingredientData['quantity']),
                    Unit::fromString($ingredientData['unit'])
                ),
            );
            $ingredients->add($ingredient);
        }
        $recipe->setIngredients($ingredients);

        $this->repository->create($recipe);
        $this->recipeProjector->createRecipe($recipe);
    }
}
