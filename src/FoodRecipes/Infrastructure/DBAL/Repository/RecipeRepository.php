<?php

namespace Przper\Tribe\FoodRecipes\Infrastructure\DBAL\Repository;

use Doctrine\DBAL\Connection;
use Przper\Tribe\FoodRecipes\Domain\Name;
use Przper\Tribe\FoodRecipes\Domain\Recipe;
use Przper\Tribe\FoodRecipes\Domain\RecipeId;
use Przper\Tribe\FoodRecipes\Domain\RecipeRepositoryInterface;

class RecipeRepository implements RecipeRepositoryInterface
{
    public function __construct(
        private Connection $connection,
    ) {}

    public function create(Recipe $recipe): void
    {
        $sql = "INSERT INTO `recipe` (`id`, `name`) VALUES(?, ?)";
        $statement = $this->connection->prepare($sql);
        $statement->bindValue(1, $recipe->getId());
        $statement->bindValue(2, $recipe->getName());
        $statement->executeQuery();
    }

    public function persist(Recipe $recipe): void
    {
        $sql = <<<SQL
                UPDATE recipe
                SET
                    id = ?,
                    name = ?
                WHERE
                    id = ?
            SQL;
        $statement = $this->connection->prepare($sql);
        $statement->bindValue(1, $recipe->getId());
        $statement->bindValue(2, $recipe->getName());
        $statement->bindValue(3, $recipe->getId());
        $statement->executeQuery();
    }

    public function get(RecipeId $id): ?Recipe
    {
        $sql = "SELECT * FROM recipe WHERE id = ?";
        $statement = $this->connection->prepare($sql);
        $statement->bindValue(1, $id);
        $result = $statement->executeQuery();

        if ($result->rowCount()) {
            $data = $result->fetchAssociative();

            return Recipe::restore(
                new RecipeId($data['id']),
                Name::fromString($data['name']),
            );
        }

        return null;
    }
}
