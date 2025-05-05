<?php

namespace Tests\Integration\Provisioning\Infrastructure\DBAL;

use Doctrine\DBAL\Connection;
use PHPUnit\Framework\Attributes\Test;
use Przper\Tribe\Provisioning\Application\Query\Result\GroceryListIndex;
use Przper\Tribe\Provisioning\Infrastructure\DBAL\Query\GetGroceryLists;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;

class GetGroceryListsTest extends KernelTestCase
{
    private GetGroceryLists $query;
    private Connection $connection;

    protected function setUp(): void
    {
        self::bootKernel();
        $container = self::getContainer();

        $this->query = $container->get(GetGroceryLists::class);
        $this->connection = $container->get(Connection::class);

        $this->connection->executeQuery(<<<SQL
            INSERT INTO tribe.provisioning_grocery_list
                (id)
            VALUES
                ('0c53c94a-d821-11ee-8fbc-0242ac190003'),
                ('d6e9c61c-a995-45af-b4c6-ea1caa9b1c26');
            SQL);
    }

    protected function tearDown(): void
    {
        $this->connection->executeQuery(<<<SQL
            DELETE FROM tribe.provisioning_grocery_list
            WHERE `id` IN (
                '0c53c94a-d821-11ee-8fbc-0242ac190003',
                'd6e9c61c-a995-45af-b4c6-ea1caa9b1c26'
            ); 
            SQL);
    }

    #[Test]
    public function it_retrieves_all_grocery_lists(): void
    {
        $groceryLists = $this->query->execute();

        $this->assertIsArray($groceryLists);
        $this->assertNotEmpty($groceryLists);
        $this->assertGreaterThanOrEqual(2, count($groceryLists));
        $this->assertInstanceOf(GroceryListIndex::class, $groceryLists[0]);
    }
}
