<?php

use Przper\Tribe\FoodRecipes\Domain\Recipe;
use Przper\Tribe\FoodRecipes\Domain\Name;
use Przper\Tribe\FoodRecipes\Domain\Ingredient;
use Przper\Tribe\FoodRecipes\Domain\Unit;
use Przper\Tribe\FoodRecipes\Domain\Quantity;
use Przper\Tribe\FoodRecipes\Domain\Ingredients;

$recipe = new Recipe();
$recipe->name = new Name("Chili Con Carne");

$ingredient1 = new Ingredient();
$ingredient1->name = new Name("Meat");
$ingredient1->unit = new Unit("kg");
$ingredient1->quantity = new Quantity("1");

$ingredients = new Ingredients();
$ingredients->add($ingredient1);

$recipe->ingredients = $ingredients;

echo json_encode($recipe) . "\n";
