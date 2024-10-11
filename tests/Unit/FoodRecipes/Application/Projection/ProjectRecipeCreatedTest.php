<?php

declare(strict_types=1);

namespace Tests\Unit\FoodRecipes\Application\Projection;

use PHPUnit\Framework\TestCase;
use Przper\Tribe\FoodRecipes\Application\Projection\ProjectRecipeCreated;
use Przper\Tribe\FoodRecipes\Domain\RecipeCreated;
use Przper\Tribe\FoodRecipes\Domain\RecipeId;
use Tests\Doubles\InMemoryInfrastructure\InMemoryRecipeProjection;
use Tests\Doubles\InMemoryInfrastructure\InMemoryRecipeRepository;
use Tests\Doubles\MotherObjects\IngredientMother;
use Tests\Doubles\MotherObjects\RecipeMother;

class ProjectRecipeCreatedTest extends TestCase
{
    public function test_it_persists_projection(): void
    {
        $recipeId = new RecipeId('1');
        $recipe = RecipeMother::new()
            ->id($recipeId)
            ->name('Chilli con Carne')
            ->addIngredient(IngredientMother::new()->kilogramsOfMeat(1.5)->build())
            ->addIngredient(IngredientMother::new()->cansOfTomatoes(3.0)->build())
            ->build();

        $repository = new InMemoryRecipeRepository();
        $repository->persist($recipe);
        $projection = new InMemoryRecipeProjection();

        $projector = new ProjectRecipeCreated($repository, $projection);

        $event = RecipeCreated::create($recipeId);

        $projector->handle($event);

        $indexProjection = $projection->getIndexProjection($recipeId->getId());
        $this->assertNotNull($indexProjection);
        $this->assertIsArray($indexProjection);
        $this->assertArrayHasKey('id', $indexProjection);
        $this->assertArrayHasKey('name', $indexProjection);
        $this->assertSame('Chilli con Carne', $indexProjection['name']);

        $detailProjection = $projection->getDetailProjection($recipeId->getId());
        $this->assertNotNull($detailProjection);
        $this->assertIsArray($detailProjection);
        $this->assertArrayHasKey('id', $detailProjection);
        $this->assertArrayHasKey('name', $detailProjection);
        $this->assertSame('Chilli con Carne', $detailProjection['name']);
        $this->assertArrayHasKey('ingredients', $detailProjection);
        $this->assertCount(2, $detailProjection['ingredients']);
        $this->assertSame('Meat: 1.5 kilogram', $detailProjection['ingredients'][0]);
        $this->assertSame('Tomatoes: 3 can', $detailProjection['ingredients'][1]);
    }
}
