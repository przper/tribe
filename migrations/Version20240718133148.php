<?php

declare(strict_types=1);

namespace Tribe\Migrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20240718133148 extends AbstractMigration
{
    public function getDescription(): string
    {
        return 'Add Ingredients to RecipeDetail projection';
    }

    public function up(Schema $schema): void
    {
        $this->addSql(
            <<<SQL
                    ALTER TABLE `projection_recipe_detail`
                        ADD `ingredients` JSON NOT NULL
                SQL
        );
    }

    public function down(Schema $schema): void
    {
        $this->addSql(
            <<<SQL
                    ALTER TABLE `projection_recipe_detail`
                        DROP COLUMN `ingredients`
                SQL
        );

    }
}
