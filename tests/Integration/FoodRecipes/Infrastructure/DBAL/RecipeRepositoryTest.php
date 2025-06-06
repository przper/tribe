<?php

namespace Tests\Integration\FoodRecipes\Infrastructure\DBAL;

use Doctrine\DBAL\Connection;
use PHPUnit\Framework\Attributes\Test;
use Przper\Tribe\FoodRecipes\Domain\Ingredient;
use Przper\Tribe\FoodRecipes\Domain\Ingredients;
use Przper\Tribe\FoodRecipes\Domain\Recipe;
use Przper\Tribe\FoodRecipes\Domain\RecipeId;
use Przper\Tribe\FoodRecipes\Infrastructure\DBAL\Repository\RecipeRepository;
use Przper\Tribe\Shared\Domain\Amount;
use Przper\Tribe\Shared\Domain\Name;
use Przper\Tribe\Shared\Domain\Quantity;
use Przper\Tribe\Shared\Domain\Unit;
use Przper\Tribe\Shared\Domain\Uuid;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;
use Tests\Doubles\MotherObjects\FoodRecipes\RecipeIdMother;

class RecipeRepositoryTest extends KernelTestCase
{
    private RecipeRepository $repository;
    private Connection $connection;

    protected function setUp(): void
    {
        self::bootKernel();
        $container = self::getContainer();

        $this->repository = $container->get(RecipeRepository::class);
        $this->connection = $container->get(Connection::class);

        $this->connection->executeQuery(<<<SQL
                INSERT INTO tribe.recipe
                    (id, name)
                VALUES
                    ('0c53c94a-d821-11ee-8fbc-0242ac190002', 'RecipeDb test')
                ON DUPLICATE KEY UPDATE `name` = VALUES(`name`);
            SQL);

        $this->assertInstanceOf(RecipeRepository::class, $this->repository);
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
        parent::tearDown();
    }

    public function test_get(): void
    {
        $id = RecipeId::fromUuid(new Uuid('0c53c94a-d821-11ee-8fbc-0242ac190002'));

        $result = $this->repository->get($id);

        $this->assertNotNull($result);
        $this->assertInstanceOf(Recipe::class, $result);
        $this->assertSame('RecipeDb test', (string) $result->getName());
    }

    #[Test]
    public function it_persists_new_objects(): void
    {
        $id = RecipeIdMother::random();

        $this->assertNull($this->repository->get($id));

        $ingredients = new Ingredients();
        $ingredients->add(Ingredient::create(
            Name::fromString('Pork'),
            Amount::create(Quantity::fromFloat(1.0), Unit::fromString('kilogram')),
        ));
        $ingredients->add(Ingredient::create(
            Name::fromString('Tomatoes'),
            Amount::create(Quantity::fromFloat(3.0), Unit::fromString('can')),
        ));

        $recipe = Recipe::create($id, Name::fromString('Chilli con Carne'), $ingredients);

        $this->repository->persist($recipe);

        /** @var Recipe $dbRecipe */
        $dbRecipe = $this->repository->get($id);

        $this->assertNotNull($dbRecipe);
        $this->assertInstanceOf(Recipe::class, $dbRecipe);
        $this->assertSame('Chilli con Carne', (string) $dbRecipe->getName());
        $this->assertCount(2, $dbRecipe->getIngredients()->getAll());

        [$dbIngredient1, $dbIngredient2] = $dbRecipe->getIngredients()->getAll();
        $this->assertSame('Pork', (string) $dbIngredient1->getName());
        $this->assertSame('1[kilogram]', (string) $dbIngredient1->getAmount());

        $this->assertSame('Tomatoes', (string) $dbIngredient2->getName());
        $this->assertSame('3[can]', (string) $dbIngredient2->getAmount());
    }

    #[Test]
    public function it_persists_existing_objects(): void
    {
        $id = RecipeId::fromUuid(new Uuid('0c53c94a-d821-11ee-8fbc-0242ac190002'));

        $recipe = $this->repository->get($id);

        $recipe->changeName(Name::fromString('Updated name'));

        $this->repository->persist($recipe);

        $recipe = $this->repository->get($id);

        $this->assertEquals('Updated name', $recipe->getName());
    }
}
