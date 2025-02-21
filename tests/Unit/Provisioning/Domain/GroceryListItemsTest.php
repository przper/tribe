<?php

namespace Tests\Unit\Provisioning\Domain;

use PHPUnit\Framework\Attributes\Test;
use PHPUnit\Framework\TestCase;
use Przper\Tribe\Provisioning\Domain\GroceryListItem;
use Przper\Tribe\Provisioning\Domain\GroceryListItems;
use Przper\Tribe\Provisioning\Domain\ItemName;
use Przper\Tribe\Provisioning\Domain\Quantity;
use Przper\Tribe\Provisioning\Domain\Unit;

class GroceryListItemsTest extends TestCase
{
    #[Test]
    public function getByItemName(): void
    {
        $sut = GroceryListItems::create();

        $name = new ItemName('Meat');
        $sut->add(GroceryListItem::create($name, new Quantity(10.0), new Unit('kilogram')));

        $this->assertInstanceOf(GroceryListItem::class, $sut->getItem($name));
        $this->assertNull($sut->getItem(new ItemName('cant find me')));
    }
}
