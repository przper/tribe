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

function printRecipe(Recipe $recipe): void
{
    echo "Recipe for \"{$recipe->getName()}\"" . "\n";

    foreach ($recipe->getIngredients()->getAll() as $i => $ingredient) {
        echo "{$i}: {$ingredient->getName()} - {$ingredient->getAmount()}" . "\n";
    }
    echo "\n";
}

$ingredients = new Ingredients();
$ingredients->add(Ingredient::create(
    Name::fromString("Meat"),
    Amount::create(Quantity::fromFloat(1.2), Unit::fromString("kg")),
));
$ingredients->add(Ingredient::create(
    Name::fromString("Beans"),
    Amount::create(Quantity::fromFloat(1), Unit::fromString("can")),
));

$recipe = Recipe::restore(
    new RecipeId('test'),
    Name::fromString("Chili Con Carne"),
    $ingredients,
);

echo "created\n";
printRecipe($recipe);

$recipe->changeName(Name::fromString("Spicy Chili con Carne"));
$recipe->addIngredient(Ingredient::create(
    Name::fromString("Jalapeno"),
    Amount::create(Quantity::fromFloat(2), Unit::fromString("pieces")),
));
$recipe->addIngredient(Ingredient::create(
    Name::fromString("Meat"),
    Amount::create(Quantity::fromFloat(0.5), Unit::fromString("kg")),
));

echo "added\n";
printRecipe($recipe);

$recipe->setIngredient(Ingredient::create(
    Name::fromString("Meat"),
    Amount::create(Quantity::fromFloat(1.5), Unit::fromString("kg")),
));

echo "updated\n";
printRecipe($recipe);
