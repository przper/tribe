<?php

namespace FoodRecipes\Domain;

use PHPUnit\Framework\TestCase;
use Przper\Tribe\FoodRecipes\Domain\Name;
use Przper\Tribe\FoodRecipes\Domain\Recipe;
use Przper\Tribe\FoodRecipes\Domain\RecipeId;

class RecipeTest extends TestCase
{
    public function test_it_can_be_created_from_valid_data(): void
    {
        $id = new RecipeId('0c53c94a-d821-11ee-8fbc-0242ac190002');
        $name = Name::fromString('Chili con Carne');

        $recipe = Recipe::create($id, $name);

        $this->assertSame('0c53c94a-d821-11ee-8fbc-0242ac190002', (string) $recipe->getId());
        $this->assertSame('Chili con Carne', (string) $recipe->getName());
    }

    public function test_it_can_be_restored_from_valid_data(): void
    {
        $id = new RecipeId('0c53c94a-d821-11ee-8fbc-0242ac190002');
        $name = Name::fromString('Chili con Carne');

        $recipe = Recipe::restore($id, $name);

        $this->assertSame('0c53c94a-d821-11ee-8fbc-0242ac190002', (string) $recipe->getId());
        $this->assertSame('Chili con Carne', (string) $recipe->getName());

    }
}