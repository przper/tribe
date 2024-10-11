<?php

namespace Przper\Tribe\FoodRecipes\Infrastructure\DBAL\Projection;

use Doctrine\DBAL\Connection;
use Przper\Tribe\FoodRecipes\Application\Projection\RecipeDetailProjectionInterface;
use Symfony\Component\DependencyInjection\Attribute\Autoconfigure;

#[Autoconfigure(public: true)]
final class RecipeDetailProjection implements RecipeDetailProjectionInterface
{
    public function __construct(
        private readonly Connection $connection,
    ) {}

    /**
     * @inheritDoc
     */
    public function createRecipe(string $recipeId, string $recipeName, array $ingredients): void
    {
        $sql = <<<SQL
                INSERT INTO `projection_recipe_detail`
                    (`recipe_id`, `name`, `ingredients`)
                VALUES
                    (?, ?, ?)
                ON DUPLICATE KEY UPDATE
                    `name` = VALUES(`name`),
                    `ingredients` = VALUES(`ingredients`)
            SQL;
        $statement = $this->connection->prepare($sql);
        $statement->bindValue(1, $recipeId);
        $statement->bindValue(2, $recipeName);
        $statement->bindValue(3, json_encode($ingredients));
        $statement->executeQuery();
    }

    public function changeRecipeName(string $recipeId, string $recipeName): void
    {
        $sql = <<<SQL
                INSERT INTO `projection_recipe_detail`
                    (`recipe_id`, `name`)
                VALUES
                    (?, ?)
                ON DUPLICATE KEY UPDATE
                    `name` = VALUES(`name`)
            SQL;
        $statement = $this->connection->prepare($sql);
        $statement->bindValue(1, $recipeId);
        $statement->bindValue(2, $recipeName);
        $statement->executeQuery();
    }

    /**
     * @inheritDoc
     */
    public function changeRecipeIngredients(string $recipeId, array $ingredients): void
    {
        $sql = <<<SQL
            UPDATE `projection_recipe_detail`
            SET `ingredients` = ?
            WHERE `recipe_id` = ?
        SQL;
        $statement = $this->connection->prepare($sql);
        $statement->bindValue(1, json_encode($ingredients));
        $statement->bindValue(2, $recipeId);
        $statement->executeQuery();
    }
}