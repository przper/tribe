<?php

namespace Tests\Integration\FoodRecipes\Infrastructure\DBAL;

use Doctrine\DBAL\Connection;
use PHPUnit\Framework\Attributes\Test;
use Przper\Tribe\FoodRecipes\Application\Query\Result\RecipeIndex;
use Przper\Tribe\FoodRecipes\Infrastructure\DBAL\Query\GetRecipes;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;

class GetRecipesTest extends KernelTestCase
{
    private GetRecipes $query;
    private Connection $connection;

    protected function setUp(): void
    {
        self::bootKernel();
        $container = self::getContainer();

        $this->query = $container->get(GetRecipes::class);
        $this->connection = $container->get(Connection::class);

        $this->connection->executeQuery(<<<SQL
                INSERT INTO tribe.recipe
                    (id, name)
                VALUES
                    ('0c53c94a-d821-11ee-8fbc-0242ac190002', 'GetRecipesTest Chilli con Carne'),
                    ('d6e9c61c-a995-45af-b4c6-ea1caa9b1c25', 'GetRecipesTest Kiełbasa od Beaty')
                ON DUPLICATE KEY UPDATE `name` = VALUES(`name`);
            SQL);
        $this->connection->executeQuery(<<<SQL
                INSERT INTO tribe.projection_recipe_index
                    (recipe_id, name)
                VALUES
                    ('0c53c94a-d821-11ee-8fbc-0242ac190002', 'GetRecipesTest Chilli con Carne projection'),
                    ('d6e9c61c-a995-45af-b4c6-ea1caa9b1c25', 'GetRecipesTest Kiełbasa od Beaty projection')
                ON DUPLICATE KEY UPDATE `name` = VALUES(`name`);
            SQL);
    }

    protected function tearDown(): void
    {
        $this->connection->executeQuery(<<<SQL
                DELETE FROM tribe.recipe
                WHERE `id` IN (
                    '0c53c94a-d821-11ee-8fbc-0242ac190002',
                    'd6e9c61c-a995-45af-b4c6-ea1caa9b1c25'
                ); 
            SQL);
    }

    #[Test]
    public function it_retrieves_all_recipes(): void
    {
        $recipes = $this->query->execute();

        $this->assertIsArray($recipes);
        $this->assertNotEmpty($recipes);
        $this->assertGreaterThanOrEqual(2, count($recipes));
        $this->assertInstanceOf(RecipeIndex::class, $recipes[0]);
    }
}
