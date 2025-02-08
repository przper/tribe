<?php

namespace Tests\Integration\FoodRecipes\Infrastructure\DBAL;

use Doctrine\DBAL\Connection;
use PHPUnit\Framework\Attributes\Test;
use Przper\Tribe\FoodRecipes\Application\Query\Result\RecipeDetail;
use Przper\Tribe\FoodRecipes\Domain\RecipeId;
use Przper\Tribe\FoodRecipes\Infrastructure\DBAL\Query\GetRecipeDetail;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;

class GetRecipeDetailTest extends KernelTestCase
{
    private GetRecipeDetail $query;
    private Connection $connection;

    protected function setUp(): void
    {
        self::bootKernel();
        $container = self::getContainer();

        $this->query = $container->get(GetRecipeDetail::class);
        $this->connection = $container->get(Connection::class);

        $this->connection->executeQuery(<<<SQL
                INSERT INTO tribe.recipe
                    (id, name)
                VALUES
                    ('e3b8ee06-7377-451c-88c1-fde290a61ac4', 'GetRecipeTest Chilli con Carne')
                ON DUPLICATE KEY UPDATE `name` = VALUES(`name`);
            SQL);
        $this->connection->executeQuery(<<<SQL
                INSERT INTO tribe.projection_recipe_detail
                    (recipe_id, name, ingredients)
                VALUES
                    (
                        'e3b8ee06-7377-451c-88c1-fde290a61ac4',
                        'GetRecipeTest Chilli con Carne projection',
                        '["Meat: 1.5 kilogram","Cheese: 0.7 kilogram"]'
                    )
                ON DUPLICATE KEY UPDATE `name` = VALUES(`name`);
            SQL);
    }

    protected function tearDown(): void
    {
        $this->connection->executeQuery(<<<SQL
                DELETE FROM tribe.recipe
                WHERE `id` IN (
                    'e3b8ee06-7377-451c-88c1-fde290a61ac4'
                ); 
            SQL);
    }

    #[Test]
    public function it_retrieves_recipe_by_id(): void
    {
        $result = $this->query->execute('e3b8ee06-7377-451c-88c1-fde290a61ac4');

        $this->assertInstanceOf(RecipeDetail::class, $result);
        $this->assertSame('e3b8ee06-7377-451c-88c1-fde290a61ac4', $result->recipe_id);
        $this->assertSame('GetRecipeTest Chilli con Carne projection', $result->name);
        $this->assertCount(2, $result->ingredients);
        $this->assertSame("Meat: 1.5 kilogram", $result->ingredients[0]);
        $this->assertSame("Cheese: 0.7 kilogram", $result->ingredients[1]);
    }
}
