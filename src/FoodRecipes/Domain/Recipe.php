<?php

declare(strict_types=1);

namespace Przper\Tribe\FoodRecipes\Domain;

use Przper\Tribe\Shared\Domain\AggregateRoot;

final class Recipe extends AggregateRoot
{
    private function __construct(
        private RecipeId $id,
        private Name $name,
        private Ingredients $ingredients,
    ) {}

    public static function create(RecipeId $id, Name $name, Ingredients $ingredients = new Ingredients()): Recipe
    {
        $recipe = new self($id, $name, $ingredients);
        $recipe->raise(RecipeCreated::create($id));

        return $recipe;
    }

    public static function restore(
        RecipeId $id,
        Name $name,
        Ingredients $ingredients
    ): Recipe {
        return new self($id, $name, $ingredients);
    }

    public function getId(): RecipeId
    {
        return $this->id;
    }

    public function getName(): Name
    {
        return $this->name;
    }

    public function update(?Name $name = null, ?Ingredients $ingredients = null): void
    {
        $isChanged = false;

        if ($name !== null) {
            $isChanged = true;
            $this->name = $name;
        }

        if ($ingredients !== null) {
            $isChanged = true;
            $this->ingredients = $ingredients;
        }

        if ($isChanged) {
            $this->raise(RecipeUpdated::create($this->id));
        }
    }

    public function getIngredients(): Ingredients
    {
        return $this->ingredients;
    }
}
