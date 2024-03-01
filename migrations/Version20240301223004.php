<?php

declare(strict_types=1);

namespace Tribe\Migrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

final class Version20240301223004 extends AbstractMigration
{
    public function getDescription(): string
    {
        return 'Create Recipe table';
    }

    public function up(Schema $schema): void
    {
        $this->addSql(<<<'SQL'
                CREATE TABLE `recipe` (
                    `id`    VARCHAR(255) DEFAULT (UUID()) PRIMARY KEY,
                    `name`  VARCHAR(255) NOT NULL
                );
            SQL);
    }

    public function down(Schema $schema): void
    {
        $this->addSql(<<<'SQL'
                DROP TABLE IF EXISTS recipe;
            SQL);
    }
}
