<?php

namespace Integration\Provisioning\Application\Query;

use PHPUnit\Framework\Attributes\Test;
use Przper\Tribe\Provisioning\Application\Query\GetGroceryList;
use Przper\Tribe\Provisioning\Application\Query\Result\GroceryList as GroceryListResult;
use Przper\Tribe\Provisioning\Domain\GroceryListId;
use Przper\Tribe\Provisioning\Domain\GroceryListItemStatus;
use Przper\Tribe\Provisioning\Domain\GroceryListRepositoryInterface;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;
use Tests\Doubles\MotherObjects\Provisioning\GroceryListIdMother;
use Tests\Doubles\MotherObjects\Provisioning\GroceryListItemMother;
use Tests\Doubles\MotherObjects\Provisioning\GroceryListMother;

class GetGroceryListTest extends KernelTestCase
{
    private GroceryListId $id;
    private GetGroceryList $sut;

    protected function setUp(): void
    {
        $this->sut = self::getContainer()->get(GetGroceryList::class);
        $this->id = GroceryListIdMother::random();

        $groceryList = GroceryListMother::new()
            ->id($this->id)
            ->addItem(
                GroceryListItemMother::new()
                    ->name('Bread')
                    ->quantity(2.0)
                    ->unit('loaf')
                    ->build()
            )
            ->addItem(
                GroceryListItemMother::new()
                    ->name('Milk')
                    ->quantity(1.0)
                    ->unit('liter')
                    ->pickedUp()
                    ->build()
            )
            ->build();

        self::getContainer()->get(GroceryListRepositoryInterface::class)->persist($groceryList);
    }

    #[Test]
    public function it_gets_grocery_list_by_id(): void
    {
        $result = $this->sut->execute($this->id);

        $this->assertInstanceOf(GroceryListResult::class, $result);
        $this->assertCount(2, $result->items);

        $this->assertSame('Bread', $result->items[0]->name);
        $this->assertSame(2.0, $result->items[0]->quantity);
        $this->assertSame('loaf', $result->items[0]->unit);
        $this->assertSame(GroceryListItemStatus::ToBuy, $result->items[0]->status);

        $this->assertSame('Milk', $result->items[1]->name);
        $this->assertSame(1.0, $result->items[1]->quantity);
        $this->assertSame('liter', $result->items[1]->unit);
        $this->assertSame(GroceryListItemStatus::PickedUp, $result->items[1]->status);
    }

    #[Test]
    public function it_returns_null_when_grocery_list_not_found(): void
    {
        $result = $this->sut->execute('non-existent-id');

        $this->assertNull($result);
    }
}
