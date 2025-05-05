<?php

declare(strict_types=1);

namespace Przper\Tribe\FoodRecipes\Domain;

use Przper\Tribe\Shared\Domain\AggregateRoot;
use Przper\Tribe\Shared\Domain\Name;

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

    public function getIngredients(): Ingredients
    {
        return $this->ingredients;
    }

    public function changeName(Name $name): void
    {
        $this->name = $name;
        $this->raise(RecipeNameChanged::create($this->id));
    }

    /**
     * Add given Ingredient to already existings amount
     */
    public function addIngredient(Ingredient $ingredient): void
    {
        $this->ingredients->add($ingredient);
        $this->raise(RecipeIngredientsChanged::create($this->id));
    }

    /**
     * Set given Ingredient to given amount
     */
    public function setIngredient(Ingredient $ingredient): void
    {
        $this->ingredients->set($ingredient);
        $this->raise(RecipeIngredientsChanged::create($this->id));
    }

    public function removeIngredient(Ingredient $ingredient): void
    {
        $this->ingredients->remove($ingredient);
        $this->raise(RecipeIngredientsChanged::create($this->id));
    }
}
