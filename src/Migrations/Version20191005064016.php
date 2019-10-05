<?php

declare(strict_types=1);

namespace AcePedigree\Migrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20191005064016 extends AbstractMigration
{
    public function getDescription() : string
    {
        return 'Added timestampable columns to entities.';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE pedigree_dog ADD createdAt DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP, ADD updatedAt DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP');
        $this->addSql('ALTER TABLE pedigree_dog CHANGE createdAt createdAt DATETIME NOT NULL, CHANGE updatedAt updatedAt DATETIME NOT NULL');

        $this->addSql('ALTER TABLE pedigree_kennel ADD createdAt DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP, ADD updatedAt DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP');
        $this->addSql('ALTER TABLE pedigree_kennel CHANGE createdAt createdAt DATETIME NOT NULL, CHANGE updatedAt updatedAt DATETIME NOT NULL');

        $this->addSql('ALTER TABLE pedigree_person ADD createdAt DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP, ADD updatedAt DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP');
        $this->addSql('ALTER TABLE pedigree_person CHANGE createdAt createdAt DATETIME NOT NULL, CHANGE updatedAt updatedAt DATETIME NOT NULL');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE pedigree_dog DROP createdAt, DROP updatedAt');
        $this->addSql('ALTER TABLE pedigree_kennel DROP createdAt, DROP updatedAt');
        $this->addSql('ALTER TABLE pedigree_person DROP createdAt, DROP updatedAt');
    }
}
