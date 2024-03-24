<?php

namespace FoodRecipes\Domain;

use PHPUnit\Framework\TestCase;
use Przper\Tribe\FoodRecipes\Domain\Name;

class NameTest extends TestCase
{
    public function test_it_can_be_created_when_data_is_valid(): void
    {
        $name = Name::fromString('Meat');

        $this->assertEquals('Meat', $name);
    }
}
