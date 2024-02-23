<?php

use Przper\Tribe\FoodRecipies\Domain\Recipe;
use Przper\Tribe\FoodRecipies\Domain\Name;
use Przper\Tribe\FoodRecipies\Domain\Ingredient;
use Przper\Tribe\FoodRecipies\Domain\Unit;
use Przper\Tribe\FoodRecipies\Domain\Quantity;
use Przper\Tribe\FoodRecipies\Domain\Ingredients;

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
