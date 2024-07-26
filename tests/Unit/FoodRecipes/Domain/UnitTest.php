<?php

namespace Tests\Unit\FoodRecipes\Domain;

use PHPUnit\Framework\Attributes\Test;
use PHPUnit\Framework\TestCase;
use Przper\Tribe\FoodRecipes\Domain\Unit;

class UnitTest extends TestCase
{
    public function test_it_can_be_created_when_data_is_valid(): void
    {
        $unit = Unit::fromString('spoon');

        $this->assertEquals('spoon', $unit);
    }

    #[Test]
    public function isEqual_to_another_Unit(): void
    {
        $this->fail('TO DO');
    }
}
