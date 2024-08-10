<?php

namespace Przper\Tribe\FoodRecipes\Domain;

use Przper\Tribe\Shared\Domain\DomainEvent;

final readonly class RecipeUpdated extends DomainEvent
{
    public const EVENT_VERSION = 1;

    public const EVENT_NAME = 'recipe_updated';

    private function __construct(string $aggregateId)
    {
        parent::__construct($aggregateId, self::EVENT_NAME, self::EVENT_VERSION);
    }

    public static function create(RecipeId $recipeId): self
    {
        return new self(
            aggregateId: $recipeId,
        );
    }
}
