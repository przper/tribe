<?php

require 'index.php';

use Przper\Tribe\FoodRecipes\Domain\Recipe;
use Przper\Tribe\FoodRecipes\Domain\Name;
use Przper\Tribe\FoodRecipes\Domain\Ingredient;
use Przper\Tribe\FoodRecipes\Domain\Unit;
use Przper\Tribe\FoodRecipes\Domain\Quantity;
use Przper\Tribe\FoodRecipes\Domain\Ingredients;
use Przper\Tribe\FoodRecipes\Domain\Amount;

$recipe = new Recipe(new Name("Chili Con Carne"));

$ingredient1 = new Ingredient(new Name("Meat"));
$ingredient1->setAmount(new Amount(new Unit("kg"), new Quantity(1)));
$ingredient2 = new Ingredient(new Name("Beans"));
$ingredient2->setAmount(new Amount(new Unit("can"), new Quantity(1)));

$ingredients = new Ingredients();
$ingredients->add($ingredient1);
$ingredients->add($ingredient2);

$recipe->setIngredients($ingredients);

echo "Recipe for \"{$recipe->getName()->value}\"" . "\n";

foreach ($ingredients->getAll() as $i => $ingredient) {
    echo "{$i}: {$ingredient->name->value}" . "\n";
}
