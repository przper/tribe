<?php

namespace Tests\Unit\Provisioning\Domain;

use PHPUnit\Framework\Attributes\Test;
use PHPUnit\Framework\TestCase;
use Przper\Tribe\Provisioning\Domain\GroceryListItem;
use Przper\Tribe\Provisioning\Domain\GroceryListItemStatus;
use Przper\Tribe\Provisioning\Domain\ItemName;
use Przper\Tribe\Provisioning\Domain\Quantity;
use Przper\Tribe\Provisioning\Domain\Unit;

class GroceryListItemTest extends TestCase
{
    #[Test]
    public function pickUp(): void
    {
        $groceryItem = GroceryListItem::create(new ItemName('Apple'), new Quantity(1), new Unit('kg'));

        $this->assertFalse($groceryItem->isPickedUp());

        $groceryItem->pickUp();

        $this->assertTrue($groceryItem->isPickedUp());
        $this->assertEquals(GroceryListItemStatus::PickedUp, $groceryItem->getStatus());
    }
}
