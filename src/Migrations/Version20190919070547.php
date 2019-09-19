<?php

declare(strict_types=1);

namespace AcePedigree\Migrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20190919070547 extends AbstractMigration
{
    public function getDescription() : string
    {
        return 'Rename ancestry table to kinship.';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('RENAME TABLE pedigree_ancestry TO pedigree_kinship');
        $this->addSql('ALTER TABLE pedigree_kinship RENAME INDEX IDX_2058874D1F91D63 TO IDX_52F7EC39D1F91D63');
        $this->addSql('ALTER TABLE pedigree_kinship RENAME INDEX IDX_2058874D3BFA33A TO IDX_52F7EC39D3BFA33A');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('RENAME TABLE pedigree_kinship TO pedigree_ancestry');
        $this->addSql('ALTER TABLE pedigree_ancestry RENAME INDEX IDX_52F7EC39D1F91D63 TO IDX_2058874D1F91D63');
        $this->addSql('ALTER TABLE pedigree_ancestry RENAME INDEX IDX_52F7EC39D3BFA33A TO IDX_2058874D3BFA33A');
    }
}
