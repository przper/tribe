<?php

namespace Tests\Doubles\MotherObjects;

use Przper\Tribe\FoodRecipes\Domain\Name;
use Przper\Tribe\FoodRecipes\Domain\Recipe;
use Przper\Tribe\FoodRecipes\Domain\RecipeId;
use Przper\Tribe\Shared\Infrastructure\Ramsey\IdGenerator;

class RecipeMother
{
    private Recipe $recipe;
    private IdGenerator $idGenerator;

    private function __construct()
    {
        $this->idGenerator = new IdGenerator();
        $this->recipe = Recipe::create(
            new RecipeId($this->idGenerator->generate()),
            Name::fromString('Test 123'),
        );
    }

    public static function new(): self
    {
        return new self();
    }

    public function build(): Recipe
    {
        $recipe = clone $this->recipe;
        $recipe->pullEvents();

        return $recipe;
    }
}
