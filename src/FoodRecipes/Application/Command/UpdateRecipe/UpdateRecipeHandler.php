<?php

namespace Przper\Tribe\FoodRecipes\Application\Command\UpdateRecipe;

use Przper\Tribe\FoodRecipes\Domain\Amount;
use Przper\Tribe\FoodRecipes\Domain\Ingredient;
use Przper\Tribe\FoodRecipes\Domain\Name;
use Przper\Tribe\FoodRecipes\Domain\Quantity;
use Przper\Tribe\FoodRecipes\Domain\RecipeId;
use Przper\Tribe\FoodRecipes\Domain\RecipeRepositoryInterface;
use Przper\Tribe\FoodRecipes\Domain\Unit;
use Przper\Tribe\Shared\Domain\DomainEventDispatcherInterface;
use Symfony\Component\DependencyInjection\Attribute\Autoconfigure;

#[Autoconfigure(public: true)]
final class UpdateRecipeHandler
{
    public function __construct(
        private RecipeRepositoryInterface $recipeRepository,
        private DomainEventDispatcherInterface $eventDispatcher,
    ) {}

    public function __invoke(UpdateRecipeCommand $command): void
    {
        $recipe = $this->recipeRepository->get(new RecipeId($command->id));

        $newName = Name::fromString($command->name);
        if (!$recipe->getName()->isEqual($newName)) {
            $recipe->changeName($newName);
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
