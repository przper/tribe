<?php

namespace Tests\Unit\FoodRecipes\Infrastructure\DBAL;

use PHPUnit\Framework\Attributes\Test;
use PHPUnit\Framework\TestCase;
use Przper\Tribe\FoodRecipes\Application\Query\Result\Recipe;
use Przper\Tribe\FoodRecipes\Domain\Name;
use Przper\Tribe\FoodRecipes\Domain\Recipe as DomainRecipe;
use Przper\Tribe\FoodRecipes\Domain\RecipeId;
use Przper\Tribe\FoodRecipes\Infrastructure\DBAL\Query\GetRecipe;
use Przper\Tribe\FoodRecipes\Infrastructure\DBAL\Repository\RecipeRepository;

class GetRecipeTest extends TestCase
{
    #[Test]
    public function it_retrieves_recipe_by_id(): void
    {
        $id = new RecipeId('1');
        $domainRecipe = DomainRecipe::create($id, Name::fromString('Chilli con Carne'));

        $mockedRecipeRepository = $this->createMock(RecipeRepository::class);
        $mockedRecipeRepository
            ->expects($this->any())
            ->method('get')
            ->willReturn($domainRecipe);

        $query = new GetRecipe($mockedRecipeRepository);

        $result = $query->execute($id);

        $this->assertInstanceOf(Recipe::class, $result);
        $this->assertSame('1', $result->id);
        $this->assertSame('Chilli con Carne', $result->name);
    }
}