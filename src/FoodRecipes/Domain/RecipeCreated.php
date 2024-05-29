<?php

namespace Przper\Tribe\FoodRecipes\Domain;

use Przper\Tribe\Shared\Domain\DomainEvent;

final readonly class RecipeCreated extends DomainEvent
{
    public static function fromRecipe(Recipe $recipe): self
    {
        return new self(
            aggregateId: (string) $recipe->getId(),
            name: $recipe->getName(),
            version: '1',
        );
    }
}
