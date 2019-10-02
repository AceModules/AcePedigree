<?php

declare(strict_types=1);

namespace AcePedigree\Migrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20191002233440 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE pedigree_person ADD countryId INT DEFAULT NULL, CHANGE name name VARCHAR(80) NOT NULL, CHANGE street street VARCHAR(50) DEFAULT NULL, CHANGE city city VARCHAR(30) DEFAULT NULL, CHANGE region region VARCHAR(30) DEFAULT NULL, CHANGE postCode postalCode VARCHAR(15) DEFAULT NULL, CHANGE phone phone VARCHAR(20) DEFAULT NULL');
        $this->addSql('ALTER TABLE pedigree_person ADD CONSTRAINT FK_F76F8BFFFBA2A6B4 FOREIGN KEY (countryId) REFERENCES pedigree_country (id)');
        $this->addSql('CREATE INDEX IDX_F76F8BFFFBA2A6B4 ON pedigree_person (countryId)');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE pedigree_person DROP FOREIGN KEY FK_F76F8BFFFBA2A6B4');
        $this->addSql('DROP INDEX IDX_F76F8BFFFBA2A6B4 ON pedigree_person');
        $this->addSql('ALTER TABLE pedigree_person DROP countryId, CHANGE name name VARCHAR(255) NOT NULL COLLATE utf8_unicode_ci, CHANGE street street VARCHAR(255) DEFAULT NULL COLLATE utf8_unicode_ci, CHANGE city city VARCHAR(255) DEFAULT NULL COLLATE utf8_unicode_ci, CHANGE region region VARCHAR(255) DEFAULT NULL COLLATE utf8_unicode_ci, CHANGE postalCode postCode VARCHAR(255) DEFAULT NULL COLLATE utf8_unicode_ci, CHANGE phone phone VARCHAR(255) DEFAULT NULL COLLATE utf8_unicode_ci');
    }
}
