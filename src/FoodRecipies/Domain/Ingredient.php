<?php

declare(strict_types=1);

namespace Przper\Tribe\FoodRecipies\Domain;

class Ingredient
{
    public Name $name;

    public Quantity $quantity;

    public Unit $unit;
}
