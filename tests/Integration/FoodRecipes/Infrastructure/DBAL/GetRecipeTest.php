<?php

namespace Tests\Integration\FoodRecipes\Infrastructure\DBAL;

use Doctrine\DBAL\Connection;
use PHPUnit\Framework\Attributes\Test;
use Przper\Tribe\FoodRecipes\Application\Query\Result\Recipe;
use Przper\Tribe\FoodRecipes\Application\Query\Result\RecipeDetail;
use Przper\Tribe\FoodRecipes\Domain\RecipeId;
use Przper\Tribe\FoodRecipes\Infrastructure\DBAL\Query\GetRecipe;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;

class GetRecipeTest extends KernelTestCase
{
    private GetRecipe $query;
    private Connection $connection;

    protected function setUp(): void
    {
        self::bootKernel();
        $container = self::getContainer();

        $this->query = $container->get(GetRecipe::class);
        $this->connection = $container->get(Connection::class);

        $this->connection->executeQuery(<<<SQL
                INSERT INTO tribe.projection_recipe_detail
                    (id, name)
                VALUES
                    ('e3b8ee06-7377-451c-88c1-fde290a61ac4', 'GetRecipeTest Chilli con Carne')
                ON DUPLICATE KEY UPDATE `name` = VALUES(`name`);
            SQL);
    }


    protected function tearDown(): void
    {
        $this->connection->executeQuery(<<<SQL
                DELETE FROM tribe.projection_recipe_detail
                WHERE `id` IN (
                    'e3b8ee06-7377-451c-88c1-fde290a61ac4'
                ); 
            SQL);
    }

    #[Test]
    public function it_retrieves_recipe_by_id(): void
    {
        $result = $this->query->execute(new RecipeId('e3b8ee06-7377-451c-88c1-fde290a61ac4'));

        $this->assertInstanceOf(RecipeDetail::class, $result);
        $this->assertSame('e3b8ee06-7377-451c-88c1-fde290a61ac4', $result->id);
        $this->assertSame('GetRecipeTest Chilli con Carne', $result->name);
    }
}
