<?php

namespace Przper\Tribe\FoodRecipes\Application\Projection;

use Przper\Tribe\FoodRecipes\Domain\Ingredient;
use Przper\Tribe\FoodRecipes\Domain\RecipeId;
use Przper\Tribe\FoodRecipes\Domain\RecipeIngredientsChanged;
use Przper\Tribe\FoodRecipes\Domain\RecipeRepositoryInterface;
use Przper\Tribe\Shared\Domain\DomainEvent;
use Przper\Tribe\Shared\Domain\DomainEventListenerInterface;
use Przper\Tribe\Shared\Domain\Uuid;

class ProjectRecipeDetailWhenRecipeIngredientsChanged implements DomainEventListenerInterface
{
    public function __construct(
        private readonly RecipeRepositoryInterface $repository,
        private readonly RecipeDetailProjectionInterface $projection,
    ) {}

    public function handle(DomainEvent $event): void
    {
        if (!$event instanceof RecipeIngredientsChanged) {
            return;
        }

        $recipe = $this->repository->get(RecipeId::fromString($event->aggregateId));

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

        $this->projection->changeRecipeIngredients(
            $recipe->getId(),
            $serializedIngredients,
        );
    }
}
