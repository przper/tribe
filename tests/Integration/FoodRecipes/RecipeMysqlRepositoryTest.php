<?php

namespace Tests\Integration\FoodRecipes;

use Doctrine\DBAL\Connection;
use Doctrine\DBAL\DriverManager;
use Doctrine\DBAL\Tools\DsnParser;
use Przper\Tribe\FoodRecipes\Domain\Name;
use Przper\Tribe\FoodRecipes\Domain\Recipe;
use Przper\Tribe\FoodRecipes\Domain\RecipeId;
use Przper\Tribe\FoodRecipes\Domain\RecipeRepositoryInterface;
use Przper\Tribe\FoodRecipes\Infrastructure\Database\RecipeMysqlRepository;
use Tests\IntegrationTestCase;

class RecipeMysqlRepositoryTest extends IntegrationTestCase
{
    private RecipeMysqlRepository $repository;

    private Connection $connection;

    protected function setUp(): void
    {
        parent::setUp();

        $this->repository = self::getContainer()[RecipeRepositoryInterface::class];

        $connectionParams = (new DsnParser())->parse($_ENV['DATABASE_URL']);
        $this->connection = DriverManager::getConnection($connectionParams);

        $this->connection->executeQuery(<<<SQL
                INSERT INTO tribe.recipe
                    (id, name)
                VALUES
                    ('0c53c94a-d821-11ee-8fbc-0242ac190002', 'RecipeDb test')
                ON DUPLICATE KEY UPDATE `name` = VALUES(`name`);
            SQL);
    }

    protected function tearDown(): void
    {
        $this->connection->executeQuery(<<<SQL
                DELETE FROM tribe.recipe
                WHERE `id` IN (
                    'test',
                    '0c53c94a-d821-11ee-8fbc-0242ac190002'
                ); 
            SQL);
    }

    public function test_get(): void
    {
        $id = new RecipeId('0c53c94a-d821-11ee-8fbc-0242ac190002');

        $result = $this->repository->get($id);

        $this->assertNotNull($result);
        $this->assertInstanceOf(Recipe::class, $result);
        $this->assertSame('RecipeDb test', (string) $result->getName());
    }

    public function test_create(): void
    {
        $id = new RecipeId('test');

        $this->assertNull($this->repository->get($id));

        $recipe = Recipe::create($id, Name::fromString('New Recipe'));
        $this->repository->create($recipe);

        $this->assertNotNull($this->repository->get($id));
    }

    public function test_persist(): void
    {
        $id = new RecipeId('0c53c94a-d821-11ee-8fbc-0242ac190002');

        $result = $this->repository->get($id);
        $this->assertNotNull($this->repository->get($id));
        $this->assertSame('RecipeDb test', (string) $result->getName());

        $recipe = Recipe::create($id, Name::fromString('I am updated name'));

        $this->repository->persist($recipe);

        $result = $this->repository->get($id);
        $this->assertNotNull($result);
        $this->assertInstanceOf(Recipe::class, $result);
        $this->assertSame('I am updated name', (string) $result->getName());
    }
}
