<?php

declare(strict_types=1);

namespace Przper\Tribe\FoodRecipes\Domain;

final class Recipe extends AggregateRoot
{
    private Ingredients $ingredients;

    public function __construct(
        private Name $name,
    ) {}

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
