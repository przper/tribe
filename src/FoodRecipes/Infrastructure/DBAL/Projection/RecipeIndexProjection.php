<?php

namespace Przper\Tribe\FoodRecipes\Infrastructure\DBAL\Projection;

use Doctrine\DBAL\Connection;
use Przper\Tribe\FoodRecipes\Application\Projection\RecipeIndexProjectionInterface;
use Symfony\Component\DependencyInjection\Attribute\Autoconfigure;

#[Autoconfigure(public: true)]
class RecipeIndexProjection implements RecipeIndexProjectionInterface
{
    public function __construct(
        private readonly Connection $connection,
    ) {}

    public function createRecipe(string $recipeId, string $recipeName): void
    {
        $sql = <<<SQL
                INSERT INTO `projection_recipe_index`
                    (`recipe_id`, `name`)
                VALUES
                    (?, ?)
                ON DUPLICATE KEY UPDATE `name` = VALUES(`name`);    
            SQL;
        $statement = $this->connection->prepare($sql);
        $statement->bindValue(1, $recipeId);
        $statement->bindValue(2, $recipeName);
        $statement->executeQuery();
    }

    public function changeRecipeName(string $recipeId, string $recipeName): void
    {
        $sql = <<<SQL
                INSERT INTO `projection_recipe_index`
                    (`recipe_id`, `name`)
                VALUES
                    (?, ?)
                ON DUPLICATE KEY UPDATE `name` = VALUES(`name`);    
            SQL;
        $statement = $this->connection->prepare($sql);
        $statement->bindValue(1, $recipeId);
        $statement->bindValue(2, $recipeName);
        $statement->executeQuery();
    }
}
