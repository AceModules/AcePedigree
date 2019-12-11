<?php

declare(strict_types=1);

namespace AcePedigree\Migrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20191211031943 extends AbstractMigration
{
    public function getDescription() : string
    {
        return 'Updated index names in kinship table.';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE pedigree_animal_kinship RENAME INDEX idx_b0b5d005d1f91d63 TO IDX_B0B5D005595BEE9E');
        $this->addSql('ALTER TABLE pedigree_animal_kinship RENAME INDEX idx_b0b5d005d3bfa33a TO IDX_B0B5D0055B1D50C7');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE pedigree_animal_kinship RENAME INDEX idx_b0b5d005595bee9e TO IDX_B0B5D005D1F91D63');
        $this->addSql('ALTER TABLE pedigree_animal_kinship RENAME INDEX idx_b0b5d0055b1d50c7 TO IDX_B0B5D005D3BFA33A');
    }
}
