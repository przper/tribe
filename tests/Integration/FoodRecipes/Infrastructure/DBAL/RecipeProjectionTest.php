<?php

namespace Tests\Integration\FoodRecipes\Infrastructure\DBAL;

use Doctrine\DBAL\Connection;
use PHPUnit\Framework\Attributes\Test;
use Przper\Tribe\FoodRecipes\Infrastructure\DBAL\Projection\RecipeProjection;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;

class RecipeProjectionTest extends KernelTestCase
{
    private RecipeProjection $projection;
    private Connection $connection;

    protected function setUp(): void
    {
        self::bootKernel();
        $container = self::getContainer();

        $this->connection = $container->get(Connection::class);
        $this->projection = $container->get(RecipeProjection::class);

        $this->connection->executeQuery(<<<SQL
                INSERT INTO tribe.recipe
                    (id, name)
                VALUES
                    ('0c53c94a-d821-11ee-8fbc-0242ac190003', 'RecipeProjector test')
                ON DUPLICATE KEY UPDATE `name` = VALUES(`name`);
            SQL);

        $this->assertInstanceOf(RecipeProjection::class, $this->projection);
    }

    protected function tearDown(): void
    {
        $this->connection->executeQuery(<<<SQL
                DELETE FROM tribe.recipe
                WHERE `id` IN (
                    '0c53c94a-d821-11ee-8fbc-0242ac190003'
                ); 
            SQL);
    }

    #[Test]
    public function it_persists_detail_projection(): void
    {
        $this->projection->persistRecipe(
            '0c53c94a-d821-11ee-8fbc-0242ac190003',
            'RecipeProjector Detail Projection test',
            ['Tomato: 1 can', 'Pork: 1 kilogram'],
        );

        $projectionSaved = $this->connection->executeQuery(
            "SELECT * FROM projection_recipe_detail WHERE recipe_id = '0c53c94a-d821-11ee-8fbc-0242ac190003'",
        )->fetchAssociative();

        $this->assertArrayHasKey('recipe_id', $projectionSaved);
        $this->assertSame('0c53c94a-d821-11ee-8fbc-0242ac190003', $projectionSaved['recipe_id']);
        $this->assertArrayHasKey('name', $projectionSaved);
        $this->assertSame('RecipeProjector Detail Projection test', $projectionSaved['name']);
        $this->assertArrayHasKey('ingredients', $projectionSaved);
        $this->assertJson($projectionSaved['ingredients']);
        $this->assertSame('["Tomato: 1 can","Pork: 1 kilogram"]', $projectionSaved['ingredients']);
    }

    #[Test]
    public function it_persists_index_projection(): void
    {
        $this->projection->persistRecipe(
            '0c53c94a-d821-11ee-8fbc-0242ac190003',
            'RecipeProjector Index Projection test',
            ['Tomato: 1 can', 'Pork: 1 kilogram'],
        );

        $projectionSaved = $this->connection->executeQuery(
            "SELECT * FROM projection_recipe_index WHERE recipe_id = '0c53c94a-d821-11ee-8fbc-0242ac190003'",
        )->fetchAssociative();

        $this->assertArrayHasKey('recipe_id', $projectionSaved);
        $this->assertSame('0c53c94a-d821-11ee-8fbc-0242ac190003', $projectionSaved['recipe_id']);
        $this->assertArrayHasKey('name', $projectionSaved);
        $this->assertSame('RecipeProjector Index Projection test', $projectionSaved['name']);
    }
}
