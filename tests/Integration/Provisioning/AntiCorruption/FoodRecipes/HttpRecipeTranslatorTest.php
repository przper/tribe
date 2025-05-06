<?php

namespace Tests\Integration\Provisioning\AntiCorruption\FoodRecipes;

use PHPUnit\Framework\Attributes\Test;
use PHPUnit\Framework\TestCase;
use Przper\Tribe\Provisioning\AntiCorruption\FoodRecipes\Recipe;
use Przper\Tribe\Provisioning\AntiCorruption\FoodRecipes\RecipeIngredient;
use Przper\Tribe\Provisioning\AntiCorruption\FoodRecipes\HttpRecipeTranslator;
use Symfony\Component\HttpClient\MockHttpClient;
use Symfony\Component\HttpClient\Response\MockResponse;
use Symfony\Contracts\HttpClient\HttpClientInterface;

class HttpRecipeTranslatorTest extends TestCase
{
    private HttpClientInterface $httpClient;
    private HttpRecipeTranslator $sut;

    protected function setUp(): void
    {
        $this->httpClient = new MockHttpClient([
            new MockResponse(
                <<<JSON
                        {
                          "id": "01927b25-67b9-708a-a00d-357e3e558ef1",
                          "name": "test 4",
                          "ingredients": [
                            {
                              "name": "Meat",
                              "quantity": 1.5,
                              "unit": "kilogram"
                            },
                            {
                              "name": "Tomatoes",
                              "quantity":3,
                              "unit": "can"
                            }
                          ]
                        }
                    JSON
            ),
        ]);

        $this->sut = new HttpRecipeTranslator($this->httpClient);
    }

    #[Test]
    public function it_loads_recipe(): void
    {
        $result = $this->sut->translate('e3b8ee06-7377-451c-88c1-fde290a61ac4');

        $this->assertInstanceOf(Recipe::class, $result);
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
