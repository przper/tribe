<?php

namespace Tests\Integration\FoodRecipes\Infrastructure\DBAL;

use Doctrine\DBAL\Connection;
use PHPUnit\Framework\Attributes\Test;
use Przper\Tribe\FoodRecipes\Infrastructure\DBAL\Projection\RecipeDetailProjection;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;

class RecipeDetailProjectionTest extends KernelTestCase
{
    private RecipeDetailProjection $projection;
    private Connection $connection;

    protected function setUp(): void
    {
        self::bootKernel();
        $container = self::getContainer();

        $this->connection = $container->get(Connection::class);
        $this->projection = $container->get(RecipeDetailProjection::class);

        $this->connection->executeQuery(<<<SQL
                INSERT INTO tribe.recipe
                    (id, name)
                VALUES
                    ('0c53c94a-d821-11ee-8fbc-0242ac190003', 'Recipe test')
                ON DUPLICATE KEY UPDATE `name` = VALUES(`name`);
            SQL);

        $this->assertInstanceOf(RecipeDetailProjection::class, $this->projection);
    }

    protected function tearDown(): void
    {
        $this->connection->executeQuery(<<<SQL
                DELETE FROM tribe.recipe
                WHERE `id` IN (
                    '0c53c94a-d821-11ee-8fbc-0242ac190003'
                ); 
            SQL);
        parent::tearDown();
    }

    #[Test]
    public function it_creates_detail_projection(): void
    {
        $projectionsCount = $this->connection->executeQuery(
            "SELECT COUNT(DISTINCT(id)) FROM projection_recipe_detail WHERE recipe_id = '0c53c94a-d821-11ee-8fbc-0242ac190003'"
        )->fetchOne();
        $this->assertEquals(0, $projectionsCount);

        $this->projection->createRecipe(
            '0c53c94a-d821-11ee-8fbc-0242ac190003',
            'RecipeProjector Detail Projection test',
            ['Tomato: 1 can', 'Pork: 1 kilogram'],
        );

        $projections = $this->connection->executeQuery(
            "SELECT * FROM projection_recipe_detail WHERE recipe_id = '0c53c94a-d821-11ee-8fbc-0242ac190003'",
        )->fetchAllAssociative();

        $this->assertCount(1, $projections);

        $projectionSaved = $projections[0];
        $this->assertArrayHasKey('recipe_id', $projectionSaved);
        $this->assertSame('0c53c94a-d821-11ee-8fbc-0242ac190003', $projectionSaved['recipe_id']);
        $this->assertArrayHasKey('name', $projectionSaved);
        $this->assertSame('RecipeProjector Detail Projection test', $projectionSaved['name']);
        $this->assertArrayHasKey('ingredients', $projectionSaved);
        $this->assertJson($projectionSaved['ingredients']);
        $this->assertSame('["Tomato: 1 can","Pork: 1 kilogram"]', $projectionSaved['ingredients']);
    }

    #[Test]
    public function it_updates_name_on_existing_detail_projection(): void
    {
        $this->connection->executeStatement(<<<SQL
                INSERT INTO tribe.projection_recipe_detail
                    (id, recipe_id, name, ingredients)
                VALUES
                    (
                        'd2801565-87b2-11ef-a5f7-0242ac140002',
                        '0c53c94a-d821-11ee-8fbc-0242ac190003',
                        'RecipeProjector Detail Projection test',
                        '["Tomato: 1 can"]'
                    )
                ;
            SQL);

        $projectionsCount = $this->connection->executeQuery(
            "SELECT COUNT(DISTINCT(id)) FROM projection_recipe_detail WHERE recipe_id = '0c53c94a-d821-11ee-8fbc-0242ac190003'"
        )->fetchOne();
        $this->assertEquals(1, $projectionsCount);

        $this->projection->changeRecipeName(
            '0c53c94a-d821-11ee-8fbc-0242ac190003',
            'I have changed',
        );

        $projections = $this->connection->executeQuery(
            "SELECT * FROM projection_recipe_detail WHERE recipe_id = '0c53c94a-d821-11ee-8fbc-0242ac190003'",
        )->fetchAllAssociative();

        $this->assertCount(1, $projections);

        $projectionSaved = $projections[0];
        $this->assertArrayHasKey('recipe_id', $projectionSaved);
        $this->assertSame('0c53c94a-d821-11ee-8fbc-0242ac190003', $projectionSaved['recipe_id']);
        $this->assertArrayHasKey('name', $projectionSaved);
        $this->assertSame('I have changed', $projectionSaved['name']);
        $this->assertArrayHasKey('ingredients', $projectionSaved);
        $this->assertJson($projectionSaved['ingredients']);
        $this->assertSame('["Tomato: 1 can"]', $projectionSaved['ingredients']);
    }


    #[Test]
    public function it_updates_ingredients_on_existing_detail_projection(): void
    {
        $this->connection->executeStatement(<<<SQL
                INSERT INTO tribe.projection_recipe_detail
                    (id, recipe_id, name, ingredients)
                VALUES
                    (
                        'd2801565-87b2-11ef-a5f7-0242ac140002',
                        '0c53c94a-d821-11ee-8fbc-0242ac190003',
                        'RecipeProjector Detail Projection test',
                        '["Tomato: 1 can"]'
                    )
                ;
            SQL);

        $projectionsCount = $this->connection->executeQuery(
            "SELECT COUNT(DISTINCT(id)) FROM projection_recipe_detail WHERE recipe_id = '0c53c94a-d821-11ee-8fbc-0242ac190003'"
        )->fetchOne();
        $this->assertEquals(1, $projectionsCount);

        $this->projection->changeRecipeIngredients(
            '0c53c94a-d821-11ee-8fbc-0242ac190003',
            ['Tomato: 1 can', 'Pork: 1 kilogram'],
        );

        $projections = $this->connection->executeQuery(
            "SELECT * FROM projection_recipe_detail WHERE recipe_id = '0c53c94a-d821-11ee-8fbc-0242ac190003'",
        )->fetchAllAssociative();

        $this->assertCount(1, $projections);

        $projectionSaved = $projections[0];
        $this->assertArrayHasKey('recipe_id', $projectionSaved);
        $this->assertSame('0c53c94a-d821-11ee-8fbc-0242ac190003', $projectionSaved['recipe_id']);
        $this->assertArrayHasKey('name', $projectionSaved);
        $this->assertSame('RecipeProjector Detail Projection test', $projectionSaved['name']);
        $this->assertArrayHasKey('ingredients', $projectionSaved);
        $this->assertJson($projectionSaved['ingredients']);
        $this->assertSame('["Tomato: 1 can","Pork: 1 kilogram"]', $projectionSaved['ingredients']);
    }
}
