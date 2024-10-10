<?php

namespace Przper\Tribe\FoodRecipes\Application\Projection;

use Przper\Tribe\FoodRecipes\Domain\Ingredient;
use Przper\Tribe\FoodRecipes\Domain\RecipeId;
use Przper\Tribe\FoodRecipes\Domain\RecipeRepositoryInterface;
use Przper\Tribe\FoodRecipes\Domain\RecipeUpdated;
use Przper\Tribe\Shared\Domain\DomainEvent;
use Przper\Tribe\Shared\Domain\DomainEventListenerInterface;

class ProjectRecipeUpdated implements DomainEventListenerInterface
{
    public function __construct(
        private readonly RecipeRepositoryInterface $repository,
        private readonly RecipeProjectionInterface $projection,
    ) {}

    public function handle(DomainEvent $event): void
    {
        if (!$event instanceof RecipeUpdated) {
            return;
        }

        $recipe = $this->repository->get(new RecipeId($event->aggregateId));

        $serializedIngredients = [];
        /** @var Ingredient $ingredient */
        foreach ($recipe->getIngredients() as $ingredient) {
            $serializedIngredients[] = sprintf(
                "%s: %s %s",
                $ingredient->getName(),
                $ingredient->getAmount()->getQuantity(),
                $ingredient->getAmount()->getUnit(),
            );
        }

        $this->projection->persistRecipe(
            $recipe->getId(),
            $recipe->getName(),
            $serializedIngredients,
        );
    }
}
