<?php

namespace Przper\Tribe\FoodRecipes\Application\Query\Result;

final readonly class Recipe
{
    public function __construct(
        public string $id,
        public string $name,
    ) {}

    public static function fromDomainModel(
        \Przper\Tribe\FoodRecipes\Domain\Recipe $domainRecipe
    ): self {
        return new self(
            id: (string) $domainRecipe->getId(),
            name: (string) $domainRecipe->getName(),
        );
    }
}
