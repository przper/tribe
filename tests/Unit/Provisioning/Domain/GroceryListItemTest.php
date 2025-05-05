<?php

namespace Tests\Unit\Provisioning\Domain;

use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\Attributes\Test;
use PHPUnit\Framework\TestCase;
use Przper\Tribe\Provisioning\Domain\GroceryListItemStatus;
use Tests\Doubles\MotherObjects\Provisioning\GroceryListItemMother;

class GroceryListItemTest extends TestCase
{
    #[Test]
    public function pickUp(): void
    {
        $groceryItem = GroceryListItemMother::new()->build();

        $this->assertFalse($groceryItem->isPickedUp());

        $groceryItem->pickUp();

        $this->assertTrue($groceryItem->isPickedUp());
        $this->assertEquals(GroceryListItemStatus::PickedUp, $groceryItem->getStatus());
    }

    #[DataProvider('is_another_list_item_data')]
    #[Test]
    public function is_another_list_item(
        string $itemName1,
        string $unit1,
        string $itemName2,
        string $unit2,
        bool $expectedResult
    ): void {
        $groceryItem1 = GroceryListItemMother::new()->name($itemName1)->unit($unit1)->build();
        $groceryItem2 = GroceryListItemMother::new()->name($itemName2)->unit($unit2)->build();

        $result = $groceryItem1->is($groceryItem2);

        $this->assertSame($expectedResult, $result);
    }

    public static function is_another_list_item_data(): \Generator
    {
        yield 'identical items should match' => ['Apple', 'kg', 'Apple', 'kg', true];
        yield 'same name but different unit should not match' => ['Apple', 'kg', 'Apple', 'piece', false];
        yield 'different name but same unit should not match' => ['Apple', 'kg', 'Banana', 'kg', false];
        yield 'completely different items should not match' => ['Apple', 'kg', 'Banana', 'piece', false];
        yield 'case sensitivity in name does not matter' => ['Apple', 'kg', 'apple', 'kg', true];
        yield 'case sensitivity in unit does not matter' => ['Apple', 'Kg', 'Apple', 'kg', true];
    }
}
