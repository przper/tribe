<?php

require 'index.php';

use Przper\Tribe\FoodRecipes\Domain\Recipe;
use Przper\Tribe\FoodRecipes\Domain\Name;
use Przper\Tribe\FoodRecipes\Domain\Ingredient;
use Przper\Tribe\FoodRecipes\Domain\Unit;
use Przper\Tribe\FoodRecipes\Domain\Quantity;
use Przper\Tribe\FoodRecipes\Domain\Ingredients;
use Przper\Tribe\FoodRecipes\Domain\Amount;
use Przper\Tribe\FoodRecipes\Domain\RecipeId;

$recipe = Recipe::restore(new RecipeId('test'), Name::fromString("Chili Con Carne"));

$ingredient1 = Ingredient::create(Name::fromString("Meat"));
$ingredient1->setAmount(Amount::create(Quantity::fromFloat(1.2), Unit::fromString("kg")));
$ingredient2 = Ingredient::create(Name::fromString("Beans"));
$ingredient2->setAmount(Amount::create(Quantity::fromFloat(1), Unit::fromString("can")));

$ingredients = new Ingredients();
$ingredients->add($ingredient1);
$ingredients->add($ingredient2);

$recipe->setIngredients($ingredients);

echo "Recipe for \"{$recipe->getName()}\"" . "\n";

foreach ($ingredients->getAll() as $i => $ingredient) {
    echo "{$i}: {$ingredient->getName()}" . "\n";
}
