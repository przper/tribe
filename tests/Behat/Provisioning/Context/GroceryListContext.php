<?php

namespace Tests\Behat\Provisioning\Context;

use Behat\Behat\Context\Context;
use Behat\Step\Given;
use Behat\Step\Then;
use Behat\Step\When;
use PHPUnit\Framework\Assert;
use Przper\Tribe\Provisioning\Domain\GroceryList;
use Przper\Tribe\Provisioning\Domain\GroceryListId;
use Przper\Tribe\Provisioning\Domain\GroceryListItem;
use Przper\Tribe\Shared\Domain\Name;
use Tests\Doubles\MotherObjects\Shared\AmountMother;

class GroceryListContext implements Context
{
    private GroceryList $groceryList;

    public function __construct()
    {
        $this->groceryList = GroceryList::create(new GroceryListId('2'));
    }

    #[When('I add the :quantity :unit of :item to the grocery list')]
    public function iAddTheToTheGroceryList(float $quantity, string $unit, string $item): void
    {
        $item = GroceryListItem::create(
            Name::fromString($item),
            AmountMother::new()->quantity($quantity)->unit($unit)->build(),
        );

        $this->groceryList->add($item);
    }

    #[Then('I should see that item :itemName is listed with :quantity :unit amount in the grocery list')]
    public function iShouldSeeThatItemIsListedWithAmountInTheGroceryList(string $itemName, float $quantity, string $unit): void
    {
        $itemName = Name::fromString($itemName);
        $item = $this->groceryList->getItemByName($itemName);

        Assert::assertInstanceOf(GroceryListItem::class, $item);
        Assert::assertEquals($itemName, $item->getName());
        Assert::assertTrue(AmountMother::new()->quantity($quantity)->unit($unit)->build()->isEqual($item->getAmount()));
    }

    #[Given('there is :itemName with amount of :quantity :unit on the grocery list')]
    public function thereIsWithAmountOfKilogramOnTheGroceryList(string $itemName, float $quantity, string $unit): void
    {
        $this->iAddTheToTheGroceryList($quantity, $unit, $itemName);
    }

    #[When('I want to remove :itemName item from the grocery list')]
    public function iWantToRemoveItemFromTheGroceryList(string $itemName): void
    {
        $this->groceryList->removeItemByName(Name::fromString($itemName));
    }

    #[Then('I should not see :itemName on the grocery list')]
    public function iShouldNotSeeOnTheGroceryList(string $itemName): void
    {
        $item = $this->groceryList->getItemByName(Name::fromString($itemName));

        Assert::assertNull($item);
    }

    #[When('I mark :itemName as PickedUp')]
    public function iMarkAsPickedUp(string $itemName): void
    {
        $this->groceryList->pickUp(Name::fromString($itemName));
    }

    #[Then('I should see :itemName as PickedUp')]
    public function iShouldSeeAsGathered(string $itemName): void
    {
        Assert::assertTrue($this->groceryList->getItemByName(Name::fromString($itemName))?->isPickedUp());
    }

    #[Then('I should see :itemName as ToBuy')]
    public function iShouldSeeAsToBuy(string $itemName): void
    {
        Assert::assertTrue($this->groceryList->getItemByName(Name::fromString($itemName))?->isToBuy());
    }

    #[Then('there is :count items with name :itemName')]
    public function thereIsANumberOfItemsWithName(int $count, string $itemName): void
    {
        Assert::assertCount($count, $this->groceryList->getItems());
    }
}
