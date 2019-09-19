<?php

declare(strict_types=1);

namespace AcePedigree\Migrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20190919014011 extends AbstractMigration
{
    public function getDescription() : string
    {
        return 'Initial database schema for AcePedigree.';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('CREATE TABLE pedigree_ancestry (covariance DOUBLE PRECISION NOT NULL, dog1Id INT NOT NULL, dog2Id INT NOT NULL, INDEX IDX_2058874D1F91D63 (dog1Id), INDEX IDX_2058874D3BFA33A (dog2Id), PRIMARY KEY(dog1Id, dog2Id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE pedigree_country (id INT AUTO_INCREMENT NOT NULL, name VARCHAR(80) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE pedigree_dog (id INT AUTO_INCREMENT NOT NULL, name VARCHAR(50) NOT NULL, callName VARCHAR(15) DEFAULT NULL, registration VARCHAR(80) DEFAULT NULL, sex INT DEFAULT NULL, birthYear INT DEFAULT NULL, birthMonth INT DEFAULT NULL, birthDay INT DEFAULT NULL, deathYear INT DEFAULT NULL, deathMonth INT DEFAULT NULL, deathDay INT DEFAULT NULL, height DOUBLE PRECISION DEFAULT NULL, weight DOUBLE PRECISION DEFAULT NULL, color VARCHAR(20) DEFAULT NULL, ofaNumber INT DEFAULT NULL, features VARCHAR(255) DEFAULT NULL, titles VARCHAR(255) DEFAULT NULL, notes LONGTEXT DEFAULT NULL, kennelId INT DEFAULT NULL, sireId INT DEFAULT NULL, damId INT DEFAULT NULL, birthCountryId INT DEFAULT NULL, homeCountryId INT DEFAULT NULL, INDEX IDX_13E11FFB380C3B8 (kennelId), INDEX IDX_13E11FFB4C18FB21 (sireId), INDEX IDX_13E11FFB261BF88 (damId), INDEX IDX_13E11FFBEE8EA8D2 (birthCountryId), INDEX IDX_13E11FFB3296987A (homeCountryId), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE pedigree_dog_breeder (dogId INT NOT NULL, breederId INT NOT NULL, INDEX IDX_CE5D1D49EF294D6D (dogId), INDEX IDX_CE5D1D49A705ED45 (breederId), PRIMARY KEY(dogId, breederId)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE pedigree_dog_owner (dogId INT NOT NULL, ownerId INT NOT NULL, INDEX IDX_A81ED81DEF294D6D (dogId), INDEX IDX_A81ED81DE05EFD25 (ownerId), PRIMARY KEY(dogId, ownerId)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE pedigree_image (id INT AUTO_INCREMENT NOT NULL, path VARCHAR(255) NOT NULL, name VARCHAR(255) NOT NULL, originalName VARCHAR(255) NOT NULL, mimeType VARCHAR(255) NOT NULL, size INT NOT NULL, dogId INT DEFAULT NULL, INDEX IDX_7AE92CA2EF294D6D (dogId), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE pedigree_kennel (id INT AUTO_INCREMENT NOT NULL, name VARCHAR(80) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE pedigree_person (id INT AUTO_INCREMENT NOT NULL, name VARCHAR(255) NOT NULL, email VARCHAR(255) DEFAULT NULL, website VARCHAR(255) DEFAULT NULL, street VARCHAR(255) DEFAULT NULL, city VARCHAR(255) DEFAULT NULL, region VARCHAR(255) DEFAULT NULL, postCode VARCHAR(255) DEFAULT NULL, phone VARCHAR(255) DEFAULT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB');
        $this->addSql('ALTER TABLE pedigree_ancestry ADD CONSTRAINT FK_2058874D1F91D63 FOREIGN KEY (dog1Id) REFERENCES pedigree_dog (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE pedigree_ancestry ADD CONSTRAINT FK_2058874D3BFA33A FOREIGN KEY (dog2Id) REFERENCES pedigree_dog (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE pedigree_dog ADD CONSTRAINT FK_13E11FFB380C3B8 FOREIGN KEY (kennelId) REFERENCES pedigree_kennel (id)');
        $this->addSql('ALTER TABLE pedigree_dog ADD CONSTRAINT FK_13E11FFB4C18FB21 FOREIGN KEY (sireId) REFERENCES pedigree_dog (id)');
        $this->addSql('ALTER TABLE pedigree_dog ADD CONSTRAINT FK_13E11FFB261BF88 FOREIGN KEY (damId) REFERENCES pedigree_dog (id)');
        $this->addSql('ALTER TABLE pedigree_dog ADD CONSTRAINT FK_13E11FFBEE8EA8D2 FOREIGN KEY (birthCountryId) REFERENCES pedigree_country (id)');
        $this->addSql('ALTER TABLE pedigree_dog ADD CONSTRAINT FK_13E11FFB3296987A FOREIGN KEY (homeCountryId) REFERENCES pedigree_country (id)');
        $this->addSql('ALTER TABLE pedigree_dog_breeder ADD CONSTRAINT FK_CE5D1D49EF294D6D FOREIGN KEY (dogId) REFERENCES pedigree_dog (id)');
        $this->addSql('ALTER TABLE pedigree_dog_breeder ADD CONSTRAINT FK_CE5D1D49A705ED45 FOREIGN KEY (breederId) REFERENCES pedigree_person (id)');
        $this->addSql('ALTER TABLE pedigree_dog_owner ADD CONSTRAINT FK_A81ED81DEF294D6D FOREIGN KEY (dogId) REFERENCES pedigree_dog (id)');
        $this->addSql('ALTER TABLE pedigree_dog_owner ADD CONSTRAINT FK_A81ED81DE05EFD25 FOREIGN KEY (ownerId) REFERENCES pedigree_person (id)');
        $this->addSql('ALTER TABLE pedigree_image ADD CONSTRAINT FK_7AE92CA2EF294D6D FOREIGN KEY (dogId) REFERENCES pedigree_dog (id)');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE pedigree_dog DROP FOREIGN KEY FK_13E11FFBEE8EA8D2');
        $this->addSql('ALTER TABLE pedigree_dog DROP FOREIGN KEY FK_13E11FFB3296987A');
        $this->addSql('ALTER TABLE pedigree_ancestry DROP FOREIGN KEY FK_2058874D1F91D63');
        $this->addSql('ALTER TABLE pedigree_ancestry DROP FOREIGN KEY FK_2058874D3BFA33A');
        $this->addSql('ALTER TABLE pedigree_dog DROP FOREIGN KEY FK_13E11FFB4C18FB21');
        $this->addSql('ALTER TABLE pedigree_dog DROP FOREIGN KEY FK_13E11FFB261BF88');
        $this->addSql('ALTER TABLE pedigree_dog_breeder DROP FOREIGN KEY FK_CE5D1D49EF294D6D');
        $this->addSql('ALTER TABLE pedigree_dog_owner DROP FOREIGN KEY FK_A81ED81DEF294D6D');
        $this->addSql('ALTER TABLE pedigree_image DROP FOREIGN KEY FK_7AE92CA2EF294D6D');
        $this->addSql('ALTER TABLE pedigree_dog DROP FOREIGN KEY FK_13E11FFB380C3B8');
        $this->addSql('ALTER TABLE pedigree_dog_breeder DROP FOREIGN KEY FK_CE5D1D49A705ED45');
        $this->addSql('ALTER TABLE pedigree_dog_owner DROP FOREIGN KEY FK_A81ED81DE05EFD25');
        $this->addSql('DROP TABLE pedigree_ancestry');
        $this->addSql('DROP TABLE pedigree_country');
        $this->addSql('DROP TABLE pedigree_dog');
        $this->addSql('DROP TABLE pedigree_dog_breeder');
        $this->addSql('DROP TABLE pedigree_dog_owner');
        $this->addSql('DROP TABLE pedigree_image');
        $this->addSql('DROP TABLE pedigree_kennel');
        $this->addSql('DROP TABLE pedigree_person');
    }
}
