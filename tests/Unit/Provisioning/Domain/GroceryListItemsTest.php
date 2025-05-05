<?php

namespace Tests\Unit\Provisioning\Domain;

use PHPUnit\Framework\Attributes\Test;
use PHPUnit\Framework\TestCase;
use Przper\Tribe\Provisioning\Domain\GroceryListItem;
use Przper\Tribe\Provisioning\Domain\GroceryListItems;
use Przper\Tribe\Shared\Domain\Name;
use Przper\Tribe\Shared\Domain\Quantity;
use Tests\Doubles\MotherObjects\Provisioning\GroceryListItemMother;
use Tests\Doubles\MotherObjects\Shared\AmountMother;

class GroceryListItemsTest extends TestCase
{
    #[Test]
    public function getByItemName(): void
    {
        $sut = GroceryListItems::create();
        $sut->add(
            GroceryListItemMother::new()
                ->name('Meat')
                ->quantity(1.0)
                ->unit('kilogram')
                ->build()
        );

        $this->assertInstanceOf(GroceryListItem::class, $sut->getItem(Name::fromString('Meat')));
        $this->assertNull($sut->getItem(Name::fromString('cant find me')));
    }

    #[Test]
    public function item_can_be_added(): void
    {
        $sut = GroceryListItems::create();
        $sut->add(GroceryListItemMother::new()->name('Water')->quantity(1.0)->unit('liter')->build());

        $this->assertCount(1, $sut);

        $sut->add(GroceryListItemMother::new()->name('Water')->quantity(0.5)->unit('liter')->build());

        $this->assertCount(1, $sut);
        $item = $sut->getItem(Name::fromString('Water'));
        $this->assertInstanceOf(GroceryListItem::class, $item);
        $this->assertTrue(AmountMother::new()->liter(1.5)->build()->isEqual($item->getAmount()));


        $sut->add(GroceryListItemMother::new()->name('Apple')->quantity(1)->unit('pcs')->build());

        $this->assertCount(2, $sut);
        $this->assertEquals(Quantity::fromFloat(1.5), $sut->getItem(Name::fromString('Water'))->getAmount()->getQuantity());
        $this->assertEquals(Quantity::fromFloat(1.0), $sut->getItem(Name::fromString('Apple'))->getAmount()->getQuantity());
    }
}
