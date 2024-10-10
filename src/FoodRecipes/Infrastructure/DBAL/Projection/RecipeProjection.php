<?php

namespace Przper\Tribe\FoodRecipes\Infrastructure\DBAL\Projection;

use Doctrine\DBAL\Connection;
use Przper\Tribe\FoodRecipes\Application\Projection\RecipeProjection as Projection;
use Symfony\Component\DependencyInjection\Attribute\Autoconfigure;

#[Autoconfigure(public: true)]
class RecipeProjection implements Projection
{
    public function __construct(
        private readonly Connection $connection,
    ) {}

    public function persistRecipe(
        string $id,
        string $name,
        array $ingredients,
    ): void {
        $this->handleIndexProjection($id, $name);
        $this->handleDetailProjection($id, $name, $ingredients);
    }

    private function handleIndexProjection(string $id, string $name): void
    {
        $sql = <<<SQL
                INSERT INTO `projection_recipe_index`
                    (`recipe_id`, `name`)
                VALUES
                    (?, ?)
                ON DUPLICATE KEY UPDATE `name` = VALUES(`name`);    
            SQL;
        $statement = $this->connection->prepare($sql);
        $statement->bindValue(1, $id);
        $statement->bindValue(2, $name);
        $statement->executeQuery();
    }

    /**
     * @param string[] $ingredients
     */
    private function handleDetailProjection(string $id, string $name, array $ingredients): void
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
        $statement->bindValue(1, $id);
        $statement->bindValue(2, $name);
        $statement->bindValue(3, json_encode($ingredients));
        $statement->executeQuery();
    }
}
