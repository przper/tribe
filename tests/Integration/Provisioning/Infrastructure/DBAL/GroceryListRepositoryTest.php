<?php

namespace Tests\Integration\Provisioning\Infrastructure\DBAL;

use Doctrine\DBAL\Connection;
use PHPUnit\Framework\Attributes\Test;
use Przper\Tribe\Provisioning\Domain\GroceryList;
use Przper\Tribe\Provisioning\Domain\GroceryListId;
use Przper\Tribe\Provisioning\Domain\GroceryListItemStatus;
use Przper\Tribe\Provisioning\Infrastructure\DBAL\Repository\GroceryListRepository;
use Przper\Tribe\Shared\Domain\Name;
use Przper\Tribe\Shared\Domain\Uuid;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;
use Tests\Doubles\MotherObjects\Provisioning\GroceryListIdMother;
use Tests\Doubles\MotherObjects\Provisioning\GroceryListItemMother;
use Tests\Doubles\MotherObjects\Shared\AmountMother;

class GroceryListRepositoryTest extends KernelTestCase
{
    private GroceryListRepository $repository;
    private Connection $connection;

    protected function setUp(): void
    {
        self::bootKernel();
        $container = self::getContainer();

        $this->repository = $container->get(GroceryListRepository::class);
        $this->connection = $container->get(Connection::class);

        $this->connection->executeQuery(<<<SQL
                INSERT INTO provisioning_grocery_list
                    (id)
                VALUES
                    ('0c53c94a-d821-11ee-8fbc-0242ac190002');
            SQL);

        $this->connection->executeQuery(<<<SQL
                INSERT INTO provisioning_grocery_list_item
                    (grocery_list_id, name, quantity, unit, status)
                VALUES
                    ('0c53c94a-d821-11ee-8fbc-0242ac190002', 'Apples', 2, 'kilogram', 'to_buy'),
                    ('0c53c94a-d821-11ee-8fbc-0242ac190002', 'Water', 1.5, 'liter', 'to_buy')
                ON DUPLICATE KEY UPDATE name = VALUES(name);
            SQL);

        $this->assertInstanceOf(GroceryListRepository::class, $this->repository);
    }

    protected function tearDown(): void
    {
        $this->connection->executeQuery(<<<SQL
                DELETE FROM provisioning_grocery_list
                WHERE id IN (
                    'test',
                    '0c53c94a-d821-11ee-8fbc-0242ac190002'
                ); 
            SQL);
        parent::tearDown();
    }

    public function test_get(): void
    {
        $id = GroceryListId::fromUuid(new Uuid('0c53c94a-d821-11ee-8fbc-0242ac190002'));

        $result = $this->repository->get($id);

        $this->assertNotNull($result);
        $this->assertInstanceOf(GroceryList::class, $result);
        $this->assertCount(2, $result->getItems());
    }

    #[Test]
    public function it_persists_new_objects(): void
    {
        $id = GroceryListIdMother::random();

        $this->assertNull($this->repository->get($id));

        $groceryList = GroceryList::create($id);

        $groceryList->add(GroceryListItemMother::new()->name('Bread')->quantity(1.0)->unit('loaf')->build());
        $milk = GroceryListItemMother::new()->name('Milk')->quantity(2.0)->unit('liter')->build();
        $milk->pickUp();
        $groceryList->add($milk);

        $this->repository->persist($groceryList);

        /** @var GroceryList $dbGroceryList */
        $dbGroceryList = $this->repository->get($id);

        $this->assertNotNull($dbGroceryList);
        $this->assertInstanceOf(GroceryList::class, $dbGroceryList);
        $this->assertCount(2, $dbGroceryList->getItems());

        [$dbItem1, $dbItem2] = iterator_to_array($dbGroceryList->getItems());
        $this->assertSame('Bread', (string) $dbItem1->getName());
        $this->assertSame('1[loaf]', (string) $dbItem1->getAmount());
        $this->assertEquals(GroceryListItemStatus::ToBuy, $dbItem1->getStatus());

        $this->assertSame('Milk', (string) $dbItem2->getName());
        $this->assertSame('2[liter]', (string) $dbItem2->getAmount());
        $this->assertEquals(GroceryListItemStatus::PickedUp, $dbItem2->getStatus());
    }

    #[Test]
    public function it_updates_existing_objects_and_changes_item_status(): void
    {
        $id = GroceryListId::fromUuid(new Uuid('0c53c94a-d821-11ee-8fbc-0242ac190002'));

        $groceryList = $this->repository->get($id);
        $this->assertNotNull($groceryList);

        $apples = $groceryList->getItemByName(Name::fromString('Apples'));
        $apples->add(GroceryListItemMother::new()->name('Apples')->quantity(1)->unit('kilogram')->build());

        $water = $groceryList->getItemByName(Name::fromString('Water'));
        $water->pickUp();

        $groceryList->add(GroceryListItemMother::new()->name('Oranges')->quantity(3)->unit('piece')->build());

        $this->repository->persist($groceryList);

        $dbGroceryList = $this->repository->get($id);
        $this->assertNotNull($dbGroceryList);

        $dbItems = iterator_to_array($dbGroceryList->getItems());
        $this->assertCount(3, $dbItems);

        $this->assertTrue(Name::fromString('Apples')->is($dbItems[0]->getName()));
        $this->assertTrue(AmountMother::new()->quantity(3)->unit('kilogram')->build()->isEqual($dbItems[0]->getAmount()));
        $this->assertFalse($dbItems[0]->isPickedUp());

        $this->assertTrue(Name::fromString('Water')->is($dbItems[1]->getName()));
        $this->assertTrue(AmountMother::new()->quantity(1.5)->unit('liter')->build()->isEqual($dbItems[1]->getAmount()));
        $this->assertTrue($dbItems[1]->isPickedUp());

        $this->assertTrue(Name::fromString('Oranges')->is($dbItems[2]->getName()));
        $this->assertTrue(AmountMother::new()->quantity(3.0)->unit('piece')->build()->isEqual($dbItems[2]->getAmount()));
        $this->assertFalse($dbItems[2]->isPickedUp());
    }
}
