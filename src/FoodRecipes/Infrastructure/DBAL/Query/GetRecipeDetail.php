<?php

namespace Przper\Tribe\FoodRecipes\Infrastructure\DBAL\Query;

use Doctrine\DBAL\Connection;
use Przper\Tribe\FoodRecipes\Application\Query\GetRecipeDetail as GetRecipeQuery;
use Przper\Tribe\FoodRecipes\Application\Query\Result\RecipeDetail;
use Przper\Tribe\FoodRecipes\Domain\RecipeId;

final class GetRecipeDetail implements GetRecipeQuery
{
    public function __construct(
        private readonly Connection $connection,
    ) {}

    public function execute(RecipeId $id): ?RecipeDetail
    {
        $sql = "SELECT * FROM projection_recipe_detail WHERE recipe_id = ?";
        $statement = $this->connection->prepare($sql);
        $statement->bindValue(1, $id);
        $sqlResult = $statement->executeQuery();

        if ($sqlResult->rowCount()) {
            $data = $sqlResult->fetchAssociative();

            return new RecipeDetail(...$data);
        }

        return null;
    }
}
