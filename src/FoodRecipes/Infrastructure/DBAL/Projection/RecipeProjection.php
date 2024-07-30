<?php

namespace Przper\Tribe\FoodRecipes\Infrastructure\DBAL\Projection;

use Doctrine\DBAL\Connection;
use Przper\Tribe\FoodRecipes\Application\Projection\RecipeProjection as Projection;

class RecipeProjection implements Projection
{
    public function __construct(
        private readonly Connection $connection,
    ) {}

    public function createRecipe(
        string $recipeId,
        string $recipeName,
        array $ingredients,
    ): void {
        $sql = "INSERT INTO `projection_recipe_index` (`recipe_id`, `name`) VALUES (?, ?)";
        $statement = $this->connection->prepare($sql);
        $statement->bindValue(1, $recipeId);
        $statement->bindValue(2, $recipeName);
        $statement->executeQuery();

        $sql = "INSERT INTO `projection_recipe_detail` (`recipe_id`, `name`, `ingredients`) VALUES (?, ?, ?)";
        $statement = $this->connection->prepare($sql);
        $statement->bindValue(1, $recipeId);
        $statement->bindValue(2, $recipeName);
        $statement->bindValue(3, json_encode($ingredients));
        $statement->executeQuery();
    }
}
