<?php

namespace Przper\Tribe\FoodRecipes\Domain;

class NegativeQuantityException extends \Exception
{
    protected $message = "Quantity must have a non-negative value";
}
