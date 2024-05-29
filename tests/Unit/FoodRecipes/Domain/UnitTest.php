<?php

namespace Tests\Unit\FoodRecipes\Domain;

use PHPUnit\Framework\TestCase;
use Przper\Tribe\FoodRecipes\Domain\Unit;

class UnitTest extends TestCase
{
    public function test_it_can_be_created_when_data_is_valid(): void
    {
        $unit = Unit::fromString('spoon');

        $this->assertEquals('spoon', $unit);
    }
}
