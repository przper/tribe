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
use Przper\Tribe\Shared\Application\IdGeneratorInterface;
use Przper\Tribe\Shared\Domain\DomainEventDispatcherInterface;

final class CreateRecipeHandler
{
    public function __construct(
        private RecipeRepositoryInterface $repository,
        private RecipeProjector $recipeProjector,
        private IdGeneratorInterface $idGenerator,
        private DomainEventDispatcherInterface $eventDispatcher,
    ) {}

    public function __invoke(CreateRecipeCommand $command): void
    {
        $recipe = Recipe::create(
            new RecipeId((string) $this->idGenerator->generate()),
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

        $this->repository->persist($recipe);
        $this->eventDispatcher->dispatch(...$recipe->pullEvents());
        $this->recipeProjector->persistRecipe($recipe);
    }
}
