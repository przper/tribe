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

        // $this->connection->executeQuery(<<<SQL
        //         INSERT INTO tribe.recipe
        //             (id, name)
        //         VALUES
        //             ('0c53c94a-d821-11ee-8fbc-0242ac190002', 'RecipeDb test')
        //         ON DUPLICATE KEY UPDATE `name` = VALUES(`name`);
        //     SQL);
    }

    protected function tearDown(): void
    {
        $this->connection->delete('projection_recipe_detail', ['id' => 'DbalProjectorRecipeDetail_1']);
    }

    #[Test]
    public function it_creates_detail_projection(): void
    {
        $this->projection->createRecipe(
            'DbalProjectorRecipeDetail_1',
            'Test Recipe',
            [],
        );

        $projectionSaved = $this->connection->executeQuery(
            "SELECT * FROM projection_recipe_detail WHERE id = 'DbalProjectorRecipeDetail_1'",
        );
        dump($projectionSaved);
    }
}
