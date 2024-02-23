<?php

declare(strict_types=1);

namespace Przper\Tribe\FoodRecipes\Domain;

class Ingredient
{
    public Name $name;

    public Quantity $quantity;

    public Unit $unit;
}
