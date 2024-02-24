<?php

declare(strict_types=1);

namespace Przper\Tribe\FoodRecipes\Domain;

final class Recipe
{
    public Name $name;

    public Ingredients $ingredients;
}
