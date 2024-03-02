<?php

namespace Przper\Tribe\FoodRecipes\Infrastructure;

use Doctrine\DBAL\Connection;
use Przper\Tribe\FoodRecipes\Domain\Recipe;
use Przper\Tribe\FoodRecipes\Domain\RecipeId;
use Przper\Tribe\FoodRecipes\Domain\RecipeRepositoryInterface;
use Przper\Tribe\FoodRecipes\Domain\Name;

class RecipeDbRepository implements RecipeRepositoryInterface
{
    public function __construct(
        private Connection $connection,
    ) {}

    public function persist(Recipe $recipe): void {}

    public function get(RecipeId $id): Recipe
    {
        $sql = "SELECT * FROM recipe WHERE id = ?";
        $statement = $this->connection->prepare($sql);
        $statement->bindValue(1, $id);
        $result = $statement->executeQuery()->fetchAssociative();

        return Recipe::restore(
            new RecipeId($result['id']),
            Name::fromString($result['name']),
        );
    }
}
