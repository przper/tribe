<?php

declare(strict_types=1);

namespace Tribe\Migrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

final class Version20240711200430 extends AbstractMigration
{
    public function getDescription(): string
    {
        return 'Create Ingredient table';
    }

    public function up(Schema $schema): void
    {
        $this->addSql(<<<'SQL'
                CREATE TABLE `ingredient` (
                    `id`        VARCHAR(255) DEFAULT (UUID()) PRIMARY KEY,
                    `recipe_id` VARCHAR(255) NOT NULL,
                    `name`      VARCHAR(255) NOT NULL,
                    `quantity`  DECIMAL(5,3) UNSIGNED NOT NULL,
                    `unit`      VARCHAR(255) NOT NULL,
                    CONSTRAINT `fk_ingredient_recipe`
                        FOREIGN KEY (recipe_id) REFERENCES recipe (id)
                        ON DELETE CASCADE
                );
            SQL);
    }

    public function down(Schema $schema): void
    {
        $this->addSql(<<<'SQL'
                DROP TABLE IF EXISTS ingredient;
            SQL);
    }
}
