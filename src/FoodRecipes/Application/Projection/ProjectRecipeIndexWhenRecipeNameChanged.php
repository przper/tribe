<?php

namespace Przper\Tribe\FoodRecipes\Application\Projection;

use Przper\Tribe\FoodRecipes\Domain\RecipeId;
use Przper\Tribe\FoodRecipes\Domain\RecipeRepositoryInterface;
use Przper\Tribe\FoodRecipes\Domain\RecipeNameChanged;
use Przper\Tribe\Shared\Domain\DomainEvent;
use Przper\Tribe\Shared\Domain\DomainEventListenerInterface;

class ProjectRecipeIndexWhenRecipeNameChanged implements DomainEventListenerInterface
{
    public function __construct(
        private readonly RecipeRepositoryInterface $repository,
        private readonly RecipeIndexProjectionInterface $projection,
    ) {}

    public function handle(DomainEvent $event): void
    {
        if (!$event instanceof RecipeNameChanged) {
            return;
        }

        $recipe = $this->repository->get(new RecipeId($event->aggregateId));

        $this->projection->changeRecipeName(
            $recipe->getId(),
            $recipe->getName(),
        );
    }
}
