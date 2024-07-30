<?php

declare(strict_types=1);

namespace Tribe\Migrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

final class Version20240712213419 extends AbstractMigration
{
    public function getDescription(): string
    {
        return 'create Projection for Recipe Index page';
    }

    public function up(Schema $schema): void
    {
        $this->addSql(<<<'SQL'
                CREATE TABLE `projection_recipe_index` (
                    `id`    VARCHAR(255) DEFAULT (UUID()) PRIMARY KEY,
                    `recipe_id` VARCHAR(255) NOT NULL,
                    `name`  VARCHAR(255) NOT NULL,
                    CONSTRAINT `fk_projection_recipe_index_recipe`
                        FOREIGN KEY (recipe_id) REFERENCES recipe (id)
                        ON DELETE CASCADE
                );
            SQL);
    }

    public function down(Schema $schema): void
    {
        $this->addSql(<<<'SQL'
                DROP TABLE IF EXISTS projection_recipe_index;
            SQL);
    }
}
