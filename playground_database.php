<?php

use Doctrine\DBAL\DriverManager;
use Doctrine\DBAL\Tools\DsnParser;
use Przper\Tribe\FoodRecipes\Domain\RecipeId;
use Przper\Tribe\FoodRecipes\Infrastructure\DBAL\Repository\RecipeRepository;

require 'index.php';

$connectionParams = (new DsnParser())->parse($_ENV['DATABASE_URL']);
// echo json_encode($connectionParams) . "\n";

$connection = DriverManager::getConnection($connectionParams);

// $sql = "SELECT * FROM recipe";
// $statement = $connection->prepare($sql);

// $result = $statement->executeQuery();
// foreach ($result->fetchAllAssociativeIndexed() as  $index => $row) {
//     echo json_encode($row) . "\n";
// }

$recipeRepository = new RecipeRepository($connection);

$recipe = $recipeRepository->get(new RecipeId('0c53c94a-d821-11ee-8fbc-0242ac190002'));
echo $recipe->getName() . "\n";

$recipe = $recipeRepository->get(new RecipeId('i don\'t exist'));
echo $recipe ? $recipe->getName() : 'not found' . "\n";
