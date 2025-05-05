<?php

namespace Tests\Integration\FoodRecipes\Application;

use PHPUnit\Framework\Attributes\Test;
use Przper\Tribe\FoodRecipes\Application\Query\GetRecipe;
use Przper\Tribe\FoodRecipes\Application\Query\Result\Ingredient;
use Przper\Tribe\FoodRecipes\Application\Query\Result\Recipe;
use Przper\Tribe\FoodRecipes\Domain\RecipeRepositoryInterface;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;
use Tests\Doubles\InMemoryInfrastructure\FoodRecipes\InMemoryRecipeRepository;
use Tests\Doubles\MotherObjects\FoodRecipes\IngredientMother;
use Tests\Doubles\MotherObjects\FoodRecipes\RecipeMother;

class GetRecipeTest extends KernelTestCase
{
    private GetRecipe $query;
    private InMemoryRecipeRepository $recipeRepository;

    protected function setUp(): void
    {
        self::bootKernel();
        $container = self::getContainer();

        $this->query = $container->get(GetRecipe::class);
        $this->recipeRepository = $container->get(RecipeRepositoryInterface::class);

        $this->recipeRepository->persist(
            RecipeMother::new()
                ->id('e3b8ee06-7377-451c-88c1-fde290a61ac4')
                ->name('GetRecipe Test')
                ->addIngredient(IngredientMother::new()->kilogramsOfMeat(1.5)->build())
                ->addIngredient(IngredientMother::new()->cansOfTomatoes(3.0)->build())
                ->build(),
        );
    }

    #[Test]
    public function it_retrieves_recipe_by_id(): void
    {
        $result = $this->query->execute('e3b8ee06-7377-451c-88c1-fde290a61ac4');

        $this->assertInstanceOf(Recipe::class, $result);
        $this->assertSame('e3b8ee06-7377-451c-88c1-fde290a61ac4', $result->id);
        $this->assertSame('GetRecipe Test', $result->name);
        $this->assertCount(2, $result->ingredients);
        [$ingredient1, $ingredient2] = $result->ingredients;

        $this->assertInstanceOf(Ingredient::class, $ingredient1);
        $this->assertEquals('Meat', $ingredient1->name);
        $this->assertEquals(1.5, $ingredient1->quantity);
        $this->assertEquals('kilogram', $ingredient1->unit);

        $this->assertInstanceOf(Ingredient::class, $ingredient2);
        $this->assertEquals('Tomatoes', $ingredient2->name);
        $this->assertEquals(3.0, $ingredient2->quantity);
        $this->assertEquals('can', $ingredient2->unit);
    }
}
