<?php

namespace Tests\Integration\Provisioning\Infrastructure\FoodRecipes;

use PHPUnit\Framework\Attributes\Test;
use Przper\Tribe\FoodRecipes\Domain\RecipeRepositoryInterface;
use Przper\Tribe\Provisioning\Infrastructure\FoodRecipes\Recipe;
use Przper\Tribe\Provisioning\Infrastructure\FoodRecipes\RecipeIngredient;
use Przper\Tribe\Provisioning\Infrastructure\FoodRecipes\RecipeTranslator;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;
use Tests\Doubles\MotherObjects\FoodRecipes\IngredientMother;
use Tests\Doubles\MotherObjects\FoodRecipes\RecipeMother;

class RecipeTranslatorTest extends KernelTestCase
{
    private RecipeTranslator $translator;

    protected function setUp(): void
    {
        self::bootKernel();

        $this->translator = self::getContainer()->get(RecipeTranslator::class);
        self::getContainer()->get(RecipeRepositoryInterface::class)->persist(
            RecipeMother::new()
            ->id('e3b8ee06-7377-451c-88c1-fde290a61ac4')
            ->name('Provisioning Recipe Test')
            ->addIngredient(IngredientMother::new()->kilogramsOfMeat(1.5)->build())
            ->addIngredient(IngredientMother::new()->cansOfTomatoes(3.0)->build())
            ->build(),
        );
    }

    #[Test]
    public function it_loads_recipe(): void
    {
        $result = $this->translator->translate('e3b8ee06-7377-451c-88c1-fde290a61ac4');

        $this->assertInstanceOf(Recipe::class, $result);
        $this->assertSame("Provisioning Recipe Test", $result->name);
        $this->assertCount(2, $result->ingredients);
        [$ingredient1, $ingredient2] = $result->ingredients;

        $this->assertInstanceOf(RecipeIngredient::class, $ingredient1);
        $this->assertEquals('Meat', $ingredient1->name);
        $this->assertEquals(1.5, $ingredient1->quantity);
        $this->assertEquals('kilogram', $ingredient1->unit);

        $this->assertInstanceOf(RecipeIngredient::class, $ingredient2);
        $this->assertEquals('Tomatoes', $ingredient2->name);
        $this->assertEquals(3.0, $ingredient2->quantity);
        $this->assertEquals('can', $ingredient2->unit);
    }
}
