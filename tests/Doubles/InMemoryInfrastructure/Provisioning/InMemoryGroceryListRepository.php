<?php

namespace Tests\Doubles\InMemoryInfrastructure\Provisioning;

use Przper\Tribe\Provisioning\Domain\GroceryList;
use Przper\Tribe\Provisioning\Domain\GroceryListId;
use Przper\Tribe\Provisioning\Domain\GroceryListRepositoryInterface;

class InMemoryGroceryListRepository implements GroceryListRepositoryInterface
{
    /**
     * @var GroceryList[] $groceryLists
     */
    private array $groceryLists = [];

    public function persist(GroceryList $groceryList): void
    {
        $this->groceryLists[(string) $groceryList->getId()] = $groceryList;
    }

    public function get(GroceryListId $id): ?GroceryList
    {
        return $this->groceryLists[(string) $id] ?? null;
    }
}
