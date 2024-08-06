<?php

namespace Przper\Tribe\FoodRecipes\Application\Command\UpdateRecipe;

use Przper\Tribe\FoodRecipes\Application\Projection\RecipeProjector;
use Przper\Tribe\FoodRecipes\Domain\Amount;
use Przper\Tribe\FoodRecipes\Domain\Ingredient;
use Przper\Tribe\FoodRecipes\Domain\Ingredients;
use Przper\Tribe\FoodRecipes\Domain\Name;
use Przper\Tribe\FoodRecipes\Domain\Quantity;
use Przper\Tribe\FoodRecipes\Domain\RecipeId;
use Przper\Tribe\FoodRecipes\Domain\RecipeRepositoryInterface;
use Przper\Tribe\FoodRecipes\Domain\Unit;
use Symfony\Component\DependencyInjection\Attribute\Autoconfigure;

#[Autoconfigure(public: true)]
final class UpdateRecipeHandler
{
    public function __construct(
        private RecipeRepositoryInterface $recipeRepository,
        private RecipeProjector $recipeProjector,
    ) {
    }

    public function __invoke(UpdateRecipeCommand $command): void
    {
        $recipe = $this->recipeRepository->get(new RecipeId($command->id));

        $recipe->setName(Name::fromString($command->name));

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

        $this->recipeRepository->persist($recipe);
        $this->recipeProjector->persistRecipe($recipe);
    }
}