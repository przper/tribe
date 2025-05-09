<?php

namespace Przper\Tribe\FoodRecipes\Application\Command\UpdateRecipe;

use Przper\Tribe\FoodRecipes\Domain\Ingredient;
use Przper\Tribe\FoodRecipes\Domain\RecipeId;
use Przper\Tribe\FoodRecipes\Domain\RecipeRepositoryInterface;
use Przper\Tribe\Shared\Domain\Amount;
use Przper\Tribe\Shared\Domain\DomainEventDispatcherInterface;
use Przper\Tribe\Shared\Domain\Name;
use Przper\Tribe\Shared\Domain\Quantity;
use Przper\Tribe\Shared\Domain\Unit;
use Przper\Tribe\Shared\Domain\Uuid;

final class UpdateRecipeHandler
{
    public function __construct(
        private RecipeRepositoryInterface $recipeRepository,
        private DomainEventDispatcherInterface $eventDispatcher,
    ) {}

    public function __invoke(UpdateRecipeCommand $command): void
    {
        $recipe = $this->recipeRepository->get(RecipeId::fromString($command->id));

        $newName = Name::fromString($command->name);
        if (!$recipe->getName()->is($newName)) {
            $recipe->changeName($newName);
        }

        foreach ($recipe->getIngredients() as $ingredient) {
            $recipe->removeIngredient($ingredient);
        }

        foreach ($command->ingredients as $ingredientData) {
            $ingredient = Ingredient::create(
                Name::fromString($ingredientData['name']),
                Amount::create(
                    Quantity::fromFloat($ingredientData['quantity']),
                    Unit::fromString($ingredientData['unit'])
                ),
            );
            $recipe->setIngredient($ingredient);
        }

        $this->recipeRepository->persist($recipe);
        $this->eventDispatcher->dispatch(...$recipe->pullEvents());
    }
}
