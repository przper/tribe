<?php

declare(strict_types=1);

namespace Przper\Tribe\FoodRecipes\Domain;

use Przper\Tribe\Shared\Domain\AggregateRoot;

final class Recipe extends AggregateRoot
{
    private Ingredients $ingredients;

    private function __construct(
        private RecipeId $id,
        private Name $name,
    ) {}

    public static function create(
        RecipeId $id,
        Name $name,
    ): Recipe
    {
        return new self($id, $name);
    }

    public static function restore(
        RecipeId $id,
        Name $name,
    ): Recipe
    {
        return new self($id, $name);
    }

    public function getId(): RecipeId
    {
        return $this->id;
    }

    public function getName(): Name
    {
        return $this->name;
    }

    public function setName(Name $name): self
    {
        $this->name = $name;

        return $this;
    }

    public function getIngredients(): Ingredients
    {
        return $this->ingredients;
    }

    public function setIngredients(Ingredients $ingredients): self
    {
        $this->ingredients = $ingredients;

        return $this;
    }
}
