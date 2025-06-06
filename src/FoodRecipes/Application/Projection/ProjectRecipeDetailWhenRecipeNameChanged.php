<?php

namespace Przper\Tribe\FoodRecipes\Application\Projection;

use Przper\Tribe\FoodRecipes\Domain\RecipeId;
use Przper\Tribe\FoodRecipes\Domain\RecipeRepositoryInterface;
use Przper\Tribe\FoodRecipes\Domain\RecipeNameChanged;
use Przper\Tribe\Shared\Domain\DomainEvent;
use Przper\Tribe\Shared\Domain\DomainEventListenerInterface;
use Przper\Tribe\Shared\Domain\Uuid;

class ProjectRecipeDetailWhenRecipeNameChanged implements DomainEventListenerInterface
{
    public function __construct(
        private readonly RecipeRepositoryInterface $repository,
        private readonly RecipeDetailProjectionInterface $projection,
    ) {}

    public function handle(DomainEvent $event): void
    {
        if (!$event instanceof RecipeNameChanged) {
            return;
        }

        $recipe = $this->repository->get(RecipeId::fromString($event->aggregateId));

        $this->projection->changeRecipeName(
            $recipe->getId(),
            $recipe->getName(),
        );
    }
}
