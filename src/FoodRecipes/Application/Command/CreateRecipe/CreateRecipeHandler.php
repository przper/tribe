<?php

namespace Przper\Tribe\FoodRecipes\Application\Command\CreateRecipe;

use Przper\Tribe\FoodRecipes\Domain\Ingredient;
use Przper\Tribe\FoodRecipes\Domain\Ingredients;
use Przper\Tribe\FoodRecipes\Domain\Recipe;
use Przper\Tribe\FoodRecipes\Domain\RecipeId;
use Przper\Tribe\FoodRecipes\Domain\RecipeRepositoryInterface;
use Przper\Tribe\Shared\Application\Command\Sync\CommandHandler;
use Przper\Tribe\Shared\Domain\Amount;
use Przper\Tribe\Shared\Domain\DomainEventDispatcherInterface;
use Przper\Tribe\Shared\Domain\Name;
use Przper\Tribe\Shared\Domain\Quantity;
use Przper\Tribe\Shared\Domain\Unit;
use Przper\Tribe\Shared\Domain\UuidGeneratorInterface;

final class CreateRecipeHandler implements CommandHandler
{
    public function __construct(
        private RecipeRepositoryInterface $repository,
        private UuidGeneratorInterface $uuidGenerator,
        private DomainEventDispatcherInterface $eventDispatcher,
    ) {}

    public function __invoke(CreateRecipeCommand $command): void
    {

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

        $recipe = Recipe::create(
            RecipeId::fromUuid($this->uuidGenerator->generate()),
            Name::fromString($command->name),
            $ingredients,
        );

        $this->repository->persist($recipe);
        $this->eventDispatcher->dispatch(...$recipe->pullEvents());
    }
}
