<?php

namespace Tests\Doubles\MotherObjects\FoodRecipes;

use Przper\Tribe\FoodRecipes\Domain\Ingredient;
use Przper\Tribe\FoodRecipes\Domain\Recipe;
use Przper\Tribe\FoodRecipes\Domain\RecipeId;
use Przper\Tribe\Shared\Domain\Name;
use Przper\Tribe\Shared\Infrastructure\Ramsey\UuidGenerator;

class RecipeMother
{
    private Recipe $recipe;
    private UuidGenerator $idGenerator;

    private function __construct()
    {
        $this->idGenerator = new UuidGenerator();
        $this->recipe = Recipe::create(
            RecipeId::fromUuid($this->idGenerator->generate()),
            Name::fromString('Test 123'),
        );
    }

    public static function new(): self
    {
        return new self();
    }

    public function id(string|RecipeId $id): self
    {
        if (is_string($id)) {
            $id = RecipeId::fromString($id);
        }

        $this->recipe = Recipe::create(
            id: $id,
            name: $this->recipe->getName(),
            ingredients: $this->recipe->getIngredients()
        );

        return $this;
    }

    public function name(string|Name $name): self
    {
        if (is_string($name)) {
            $name = Name::fromString($name);
        }

        $this->recipe->changeName($name);

        return $this;
    }

    public function addIngredient(Ingredient $ingredient): self
    {
        $this->recipe->addIngredient($ingredient);
        return $this;
    }

    public function build(): Recipe
    {
        $recipe = clone $this->recipe;
        $recipe->pullEvents();

        return $recipe;
    }
}
