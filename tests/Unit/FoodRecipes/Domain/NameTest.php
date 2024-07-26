<?php

namespace Tests\Unit\FoodRecipes\Domain;

use PHPUnit\Framework\Attributes\Test;
use PHPUnit\Framework\TestCase;
use Przper\Tribe\FoodRecipes\Domain\Name;

class NameTest extends TestCase
{
    public function test_it_can_be_created_when_data_is_valid(): void
    {
        $name = Name::fromString('Meat');

        $this->assertEquals('Meat', $name);
    }

    #[Test]
    public function isEqual_to_another_Name(): void
    {
        $name1 = Name::fromString('Meat');
        $name2 = Name::fromString('Cheese');
        $name3 = Name::fromString('Meat');

        $this->assertTrue($name1->isEqual($name3));
        $this->assertTrue($name1->isEqual($name1));
        $this->assertTrue($name3->isEqual($name1));
        $this->assertFalse($name1->isEqual($name2));
        $this->assertFalse($name2->isEqual($name1));
    }
}
