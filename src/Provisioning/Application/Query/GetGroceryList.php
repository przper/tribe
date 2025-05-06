<?php

namespace Przper\Tribe\Provisioning\Application\Query;

use Przper\Tribe\Provisioning\Application\Query\Result\GroceryList;
use Przper\Tribe\Provisioning\Application\Query\Result\GroceryListItem;
use Przper\Tribe\Provisioning\Domain\GroceryList as GroceryListQuery;
use Przper\Tribe\Provisioning\Domain\GroceryListId;
use Przper\Tribe\Provisioning\Domain\GroceryListRepositoryInterface;

final readonly class GetGroceryList
{
    public function __construct(
        private GroceryListRepositoryInterface $groceryListRepository,
    ) {}

    public function execute(string $groceryListId): ?GroceryList
    {
        $groceryList = $this->groceryListRepository->get(new GroceryListId($groceryListId));

        if (!$groceryList instanceof GroceryListQuery) {
            return null;
        }

        $groceryListItems = [];
        foreach ($groceryList->getItems() as $item) {
            $groceryListItems[] = new GroceryListItem(
                name: (string) $item->getName(),
                quantity: (float) (string) $item->getAmount()->getQuantity(),
                unit: (string) $item->getAmount()->getUnit(),
                status: $item->getStatus(),
            );
        }

        return new GroceryList($groceryListItems);
    }
}
