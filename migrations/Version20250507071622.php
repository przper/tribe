<?php

declare(strict_types=1);

namespace Tribe\Migrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

final class Version20250507071622 extends AbstractMigration
{
    public function getDescription(): string
    {
        return 'Create `identity_user` table';
    }

    public function up(Schema $schema): void
    {
        $this->addSql(<<<'SQL'
            CREATE TABLE `identity_user` (
                `id`         VARCHAR(255) DEFAULT (UUID()) PRIMARY KEY,
                `name`       VARCHAR(255) NOT NULL,
                `email`      VARCHAR(255) NOT NULL,
                `password`   VARCHAR(255) NOT NULL,
                `token`      VARCHAR(255) NOT NULL,
                `created_at` DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP(),
                `updated_at` DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP() ON UPDATE CURRENT_TIMESTAMP()
            );
        SQL);
    }

    public function down(Schema $schema): void
    {
        $this->addSql("DROP TABLE IF EXISTS `identity_user`");
    }
}
