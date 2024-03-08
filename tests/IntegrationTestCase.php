<?php

namespace Tests;

use Doctrine\DBAL\DriverManager;
use Doctrine\DBAL\Tools\DsnParser;
use Dotenv\Dotenv;
use PHPUnit\Framework\TestCase;
use Przper\Tribe\FoodRecipes\Domain\RecipeRepositoryInterface;
use Przper\Tribe\FoodRecipes\Infrastructure\RecipeDbRepository;

class IntegrationTestCase extends TestCase
{
    /** @var array<class-string, object>  */
    private array $container = [];

    protected function setUp(): void
    {
        parent::setUp();

        $dotenv = Dotenv::createImmutable(__DIR__ . '/..');
        $dotenv->load();

        $connectionParams = (new DsnParser())->parse($_ENV['DATABASE_URL']);
        $connection = DriverManager::getConnection($connectionParams);

        $this->container = [
            RecipeRepositoryInterface::class => new RecipeDbRepository($connection),
        ];
    }

    public function getContainer(): array
    {
        return $this->container;
    }
}
