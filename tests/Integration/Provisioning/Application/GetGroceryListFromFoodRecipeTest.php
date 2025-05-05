<?php

namespace Tests\Integration\Provisioning\Application;

use PHPUnit\Framework\Attributes\Test;
use Przper\Tribe\FoodRecipes\Domain\RecipeRepositoryInterface;
use Przper\Tribe\Provisioning\Application\Query\GetGroceryListFromFoodRecipe;
use Przper\Tribe\Provisioning\Application\Query\Result\GroceryList;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;
use Tests\Doubles\MotherObjects\FoodRecipes\IngredientMother;
use Tests\Doubles\MotherObjects\FoodRecipes\RecipeMother;

class GetGroceryListFromFoodRecipeTest extends KernelTestCase
{
    private GetGroceryListFromFoodRecipe $sut;

    protected function setUp(): void
    {
        $this->sut = self::getContainer()->get(GetGroceryListFromFoodRecipe::class);

        self::getContainer()->get(RecipeRepositoryInterface::class)->persist(
            RecipeMother::new()
                ->id('e3b8ee06-7377-451c-88c1-fde290a61ac4')
                ->name('GetRecipe Test')
                ->addIngredient(IngredientMother::new()->kilogramsOfMeat(1.5)->build())
                ->addIngredient(IngredientMother::new()->cansOfTomatoes(3.0)->build())
                ->build(),
        );
    }

    #[Test]
    public function it_extracts_GroceryList_from_Recipe(): void
    {
        $groceryList = $this->sut->execute('e3b8ee06-7377-451c-88c1-fde290a61ac4');

        $this->assertInstanceOf(GroceryList::class, $groceryList);
        $this->assertCount(2, $groceryList->items);
        $this->assertArrayHasKey(0, $groceryList->items);
        $this->assertSame("Meat", $groceryList->items[0]->name);
        $this->assertSame(1.5, $groceryList->items[0]->quantity);
        $this->assertSame("kilogram", $groceryList->items[0]->unit);

        $this->assertArrayHasKey(1, $groceryList->items);
        $this->assertSame("Tomatoes", $groceryList->items[1]->name);
        $this->assertSame(3.0, $groceryList->items[1]->quantity);
        $this->assertSame("can", $groceryList->items[1]->unit);
    }
}