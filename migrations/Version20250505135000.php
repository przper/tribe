<?php

declare(strict_types=1);

namespace Tribe\Migrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

final class Version20250505135000 extends AbstractMigration
{
    public function getDescription(): string
    {
        return 'Create GroceryList and GroceryListItem tables';
    }

    public function up(Schema $schema): void
    {
        $this->addSql(<<<'SQL'
                CREATE TABLE `provisioning_grocery_list` (
                    `id` VARCHAR(255) DEFAULT (UUID()) PRIMARY KEY
                );
            SQL);

        $this->addSql(<<<'SQL'
                CREATE TABLE `provisioning_grocery_list_item` (
                    `id` VARCHAR(255) DEFAULT (UUID()) PRIMARY KEY,
                    `grocery_list_id` VARCHAR(255) NOT NULL,
                    `name` VARCHAR(255) NOT NULL,
                    `quantity` DECIMAL(5,3) NOT NULL,
                    `unit` VARCHAR(50) NOT NULL,
                    `status` VARCHAR(50) NOT NULL DEFAULT 'to_buy',
                    FOREIGN KEY (`grocery_list_id`) REFERENCES `provisioning_grocery_list` (`id`) ON DELETE CASCADE
                );
            SQL);
    }

    public function down(Schema $schema): void
    {
        $this->addSql(<<<'SQL'
                DROP TABLE IF EXISTS provisioning_grocery_list_item;
            SQL);

        $this->addSql(<<<'SQL'
                DROP TABLE IF EXISTS provisioning_grocery_list;
            SQL);
    }
}
