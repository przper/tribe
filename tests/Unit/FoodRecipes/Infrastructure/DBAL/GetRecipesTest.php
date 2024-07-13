<?php

namespace FoodRecipes\Infrastructure\DBAL;

use PHPUnit\Framework\Attributes\Test;
use PHPUnit\Framework\TestCase;
use Przper\Tribe\FoodRecipes\Application\Query\Result\Recipe;
use Przper\Tribe\FoodRecipes\Application\Query\Result\RecipeDetail;
use Przper\Tribe\FoodRecipes\Domain\Name;
use Przper\Tribe\FoodRecipes\Domain\Recipe as DomainRecipe;
use Przper\Tribe\FoodRecipes\Domain\RecipeId;
use Przper\Tribe\FoodRecipes\Infrastructure\DBAL\Query\GetRecipe;
use Przper\Tribe\FoodRecipes\Infrastructure\DBAL\Repository\RecipeRepository;

class GetRecipesTest extends TestCase
{
    #[Test]
    public function it_retrieves_all_recipes(): void
    {
        $this->fail('TO DO');
    }
}
