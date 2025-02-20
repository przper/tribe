<?php

namespace Tests\Behat\Provisioning\Context;

use Behat\Step\Then;
use Behat\Step\When;
use Behat\Behat\Context\Context;
use PHPUnit\Framework\Assert;
use Przper\Tribe\Provisioning\Domain\GroceryList;
use Przper\Tribe\Provisioning\Domain\GroceryListItem;
use Przper\Tribe\Provisioning\Domain\ItemName;
use Przper\Tribe\Provisioning\Domain\Quantity;
use Przper\Tribe\Provisioning\Domain\Unit;

class GroceryListContext implements Context
{
    private GroceryList $groceryList;

    public function __construct()
    {
        $this->groceryList = GroceryList::create();
    }

    #[When('I add the :quantity :unit of :item to the grocery list')]
    public function iAddTheToTheGroceryList(float $quantity, string $unit, string $item): void
    {
        $item = GroceryListItem::create(
            new ItemName($item),
            new Quantity($quantity),
            new Unit($unit),
        );

        $this->groceryList->add($item);
    }

    #[Then('I should see that item :itemName is listed with :quantity :unit amount in the grocery list')]
    public function iShouldSeeThatItemIsListedWithAmountInTheGroceryList(string $itemName, float $quantity, string $unit): void
    {
        $item = $this->groceryList->getItemByName($itemName);

        Assert::assertInstanceOf(GroceryListItem::class, $item);
        Assert::assertEquals(new ItemName($itemName), $item->getItemName());
        Assert::assertEquals(new Quantity($quantity), $item->getQuantity());
        Assert::assertEquals(new Unit($unit), $item->getUnit());
    }
}
