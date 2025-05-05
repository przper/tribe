<?php

namespace Przper\Tribe\Provisioning\Infrastructure\DBAL\Query;

use Doctrine\DBAL\Connection;
use Przper\Tribe\Provisioning\Application\Query\GetGroceryLists as GetGroceryListsQuery;
use Przper\Tribe\Provisioning\Application\Query\Result\GroceryListIndex;
use Symfony\Component\DependencyInjection\Attribute\Autoconfigure;

#[Autoconfigure(public: true)]
final class GetGroceryLists implements GetGroceryListsQuery
{
    public function __construct(
        private Connection $connection,
    ) {}

    /**
     * @inheritDoc
     */
    public function execute(): array
    {
        $lists = $this->connection->fetchAllAssociative('SELECT id FROM provisioning_grocery_list');

        return array_map(fn(array $list) => new GroceryListIndex($list['id']), $lists);
    }
}
