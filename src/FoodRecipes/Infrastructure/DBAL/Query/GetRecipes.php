<?php

namespace Przper\Tribe\FoodRecipes\Infrastructure\DBAL\Query;

use Doctrine\DBAL\Connection;
use Przper\Tribe\FoodRecipes\Application\Query\GetRecipes as GetRecipesQuery;
use Przper\Tribe\FoodRecipes\Application\Query\Result\RecipeIndex;

final class GetRecipes implements GetRecipesQuery
{
    public function __construct(
        private readonly Connection $connection,
    ) {
    }

    /**
     * @inheritDoc
     */
    public function execute(): array
    {
        $sql = "SELECT * FROM projection_recipe_index";
        $statement = $this->connection->prepare($sql);
        $sqlResult = $statement->executeQuery();

        $result = [];

        foreach ($sqlResult->iterateAssociative() as $db) {
            $result[] = new RecipeIndex($db['id'], $db['name']);
        }

        return $result;
    }
}