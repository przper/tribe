<?php

namespace Przper\Tribe\Provisioning\Infrastructure\DBAL\Repository;

use Doctrine\DBAL\Connection;
use Doctrine\DBAL\Exception;
use Przper\Tribe\Provisioning\Domain\GroceryList;
use Przper\Tribe\Provisioning\Domain\GroceryListId;
use Przper\Tribe\Provisioning\Domain\GroceryListItem;
use Przper\Tribe\Provisioning\Domain\GroceryListItemStatus;
use Przper\Tribe\Provisioning\Domain\GroceryListItems;
use Przper\Tribe\Provisioning\Domain\GroceryListRepositoryInterface;
use Przper\Tribe\Shared\Domain\Amount;
use Przper\Tribe\Shared\Domain\Name;
use Przper\Tribe\Shared\Domain\Quantity;
use Przper\Tribe\Shared\Domain\Unit;
use Symfony\Component\DependencyInjection\Attribute\Autoconfigure;

#[Autoconfigure(public: true)]
class GroceryListRepository implements GroceryListRepositoryInterface
{
    public function __construct(
        private Connection $connection,
    ) {}

    public function get(GroceryListId $id): ?GroceryList
    {
        $groceryListStatement = $this->connection->prepare("SELECT * FROM provisioning_grocery_list WHERE id = ?");
        $groceryListStatement->bindValue(1, $id);
        $groceryListData = $groceryListStatement->executeQuery();

        if ($groceryListData->rowCount()) {
            $groceryListData = $groceryListData->fetchAssociative();

            $itemsStatement = $this->connection->prepare("SELECT * FROM provisioning_grocery_list_item WHERE grocery_list_id = ?");
            $itemsStatement->bindValue(1, $id);
            $itemsData = $itemsStatement->executeQuery();

            $items = GroceryListItems::create();
            foreach ($itemsData->fetchAllAssociative() as $itemData) {
                $status = $itemData['status'] === 'picked_up'
                    ? GroceryListItemStatus::PickedUp
                    : GroceryListItemStatus::ToBuy;

                $item = GroceryListItem::restore(
                    Name::fromString($itemData['name']),
                    Amount::create(
                        Quantity::fromFloat((float) $itemData['quantity']),
                        Unit::fromString($itemData['unit']),
                    ),
                    $status
                );

                $items->add($item);
            }

            return GroceryList::restore(
                $id,
                $items,
            );
        }

        return null;
    }

    /**
     * @throws Exception
     * @throws \Throwable
     */
    public function persist(GroceryList $groceryList): void
    {
        $this->connection->beginTransaction();

        try {
            $statement = $this->connection->prepare(<<<SQL
                    INSERT INTO provisioning_grocery_list
                        (id)
                    VALUES
                        (?)
                    ON DUPLICATE KEY UPDATE id = id;
                SQL);
            $statement->bindValue(1, $groceryList->getId());
            $statement->executeStatement();

            $this->connection->delete('provisioning_grocery_list_item', ['grocery_list_id' => $groceryList->getId()]);

            foreach ($groceryList->getItems() as $item) {
                $status = $item->getStatus() === GroceryListItemStatus::PickedUp ? 'picked_up' : 'to_buy';

                $this->connection->insert('provisioning_grocery_list_item', [
                    'grocery_list_id' => $groceryList->getId(),
                    'name' => $item->getName(),
                    'quantity' => $item->getAmount()->getQuantity(),
                    'unit' => $item->getAmount()->getUnit(),
                    'status' => $status,
                ]);
            }

            $this->connection->commit();
        } catch (\Throwable $e) {
            $this->connection->rollBack();
            throw $e;
        }
    }
}
