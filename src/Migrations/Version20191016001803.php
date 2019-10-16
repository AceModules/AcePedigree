<?php

declare(strict_types=1);

namespace AcePedigree\Migrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20191016001803 extends AbstractMigration
{
    public function getDescription() : string
    {
        return 'Renamed dog/kennel tables to animal/house.';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('RENAME TABLE pedigree_dog TO pedigree_animal, pedigree_dog_breeder TO pedigree_animal_breeder, pedigree_dog_owner TO pedigree_animal_owner, pedigree_dog_kinship TO pedigree_animal_kinship, pedigree_dog_statistics TO pedigree_animal_statistics, pedigree_kennel TO pedigree_house');
        $this->addSql('ALTER TABLE pedigree_animal DROP FOREIGN KEY FK_13E11FFB380C3B8');
        $this->addSql('DROP INDEX IDX_13E11FFB380C3B8 ON pedigree_animal');
        $this->addSql('ALTER TABLE pedigree_animal CHANGE kennelid houseId INT DEFAULT NULL');
        $this->addSql('ALTER TABLE pedigree_animal ADD CONSTRAINT FK_A9187996B1575878 FOREIGN KEY (houseId) REFERENCES pedigree_house (id)');
        $this->addSql('CREATE INDEX IDX_A9187996B1575878 ON pedigree_animal (houseId)');
        $this->addSql('ALTER TABLE pedigree_animal RENAME INDEX idx_13e11ffb4c18fb21 TO IDX_A91879964C18FB21');
        $this->addSql('ALTER TABLE pedigree_animal RENAME INDEX idx_13e11ffb261bf88 TO IDX_A9187996261BF88');
        $this->addSql('ALTER TABLE pedigree_animal RENAME INDEX idx_13e11ffbee8ea8d2 TO IDX_A9187996EE8EA8D2');
        $this->addSql('ALTER TABLE pedigree_animal RENAME INDEX idx_13e11ffb3296987a TO IDX_A91879963296987A');
        $this->addSql('ALTER TABLE pedigree_animal_breeder DROP FOREIGN KEY FK_CE5D1D49EF294D6D');
        $this->addSql('DROP INDEX IDX_CE5D1D49EF294D6D ON pedigree_animal_breeder');
        $this->addSql('ALTER TABLE pedigree_animal_breeder DROP PRIMARY KEY');
        $this->addSql('ALTER TABLE pedigree_animal_breeder CHANGE dogid animalId INT NOT NULL');
        $this->addSql('ALTER TABLE pedigree_animal_breeder ADD CONSTRAINT FK_53F8998458056B5 FOREIGN KEY (animalId) REFERENCES pedigree_animal (id)');
        $this->addSql('CREATE INDEX IDX_53F8998458056B5 ON pedigree_animal_breeder (animalId)');
        $this->addSql('ALTER TABLE pedigree_animal_breeder ADD PRIMARY KEY (animalId, breederId)');
        $this->addSql('ALTER TABLE pedigree_animal_breeder RENAME INDEX idx_ce5d1d49a705ed45 TO IDX_53F8998A705ED45');
        $this->addSql('ALTER TABLE pedigree_animal_owner DROP FOREIGN KEY FK_A81ED81DEF294D6D');
        $this->addSql('DROP INDEX IDX_A81ED81DEF294D6D ON pedigree_animal_owner');
        $this->addSql('ALTER TABLE pedigree_animal_owner DROP PRIMARY KEY');
        $this->addSql('ALTER TABLE pedigree_animal_owner CHANGE dogid animalId INT NOT NULL');
        $this->addSql('ALTER TABLE pedigree_animal_owner ADD CONSTRAINT FK_39A1649F458056B5 FOREIGN KEY (animalId) REFERENCES pedigree_animal (id)');
        $this->addSql('CREATE INDEX IDX_39A1649F458056B5 ON pedigree_animal_owner (animalId)');
        $this->addSql('ALTER TABLE pedigree_animal_owner ADD PRIMARY KEY (animalId, ownerId)');
        $this->addSql('ALTER TABLE pedigree_animal_owner RENAME INDEX idx_a81ed81de05efd25 TO IDX_39A1649FE05EFD25');
        $this->addSql('ALTER TABLE pedigree_animal_kinship CHANGE dog1id animal1Id INT NOT NULL, CHANGE dog2id animal2Id INT NOT NULL');
        $this->addSql('ALTER TABLE pedigree_animal_kinship RENAME INDEX idx_7bd744d4d1f91d63 TO IDX_B0B5D005D1F91D63');
        $this->addSql('ALTER TABLE pedigree_animal_kinship RENAME INDEX idx_7bd744d4d3bfa33a TO IDX_B0B5D005D3BFA33A');
        $this->addSql('ALTER VIEW pedigree_animal_statistics AS SELECT x.id AS animalId, c.covariance - 1 AS inbreedingCoefficient, AVG(COALESCE(a.covariance,b.covariance,0)) AS averageCovariance FROM pedigree_animal x JOIN pedigree_animal y LEFT JOIN pedigree_animal_kinship a ON (a.animal1Id = x.id AND a.animal2Id = y.id) LEFT JOIN pedigree_animal_kinship b ON (b.animal1Id = y.id AND b.animal2Id = x.id) LEFT JOIN pedigree_animal_kinship c ON (c.animal1Id = x.id AND c.animal2Id = x.id) GROUP BY x.id');
        $this->addSql('ALTER TABLE pedigree_image DROP FOREIGN KEY FK_7AE92CA2EF294D6D');
        $this->addSql('DROP INDEX IDX_7AE92CA2EF294D6D ON pedigree_image');
        $this->addSql('ALTER TABLE pedigree_image CHANGE dogid animalId INT DEFAULT NULL');
        $this->addSql('ALTER TABLE pedigree_image ADD CONSTRAINT FK_7AE92CA2458056B5 FOREIGN KEY (animalId) REFERENCES pedigree_animal (id)');
        $this->addSql('CREATE INDEX IDX_7AE92CA2458056B5 ON pedigree_image (animalId)');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('RENAME TABLE pedigree_animal TO pedigree_dog, pedigree_animal_breeder TO pedigree_dog_breeder, pedigree_animal_owner TO pedigree_dog_owner, pedigree_animal_kinship TO pedigree_dog_kinship, pedigree_animal_statistics TO pedigree_dog_statistics, pedigree_house TO pedigree_kennel');
        $this->addSql('ALTER TABLE pedigree_dog DROP FOREIGN KEY FK_A9187996B1575878');
        $this->addSql('DROP INDEX IDX_A9187996B1575878 ON pedigree_dog');
        $this->addSql('ALTER TABLE pedigree_dog CHANGE houseid kennelId INT DEFAULT NULL');
        $this->addSql('ALTER TABLE pedigree_dog ADD CONSTRAINT FK_13E11FFB380C3B8 FOREIGN KEY (kennelId) REFERENCES pedigree_kennel (id) ON UPDATE NO ACTION ON DELETE NO ACTION');
        $this->addSql('CREATE INDEX IDX_13E11FFB380C3B8 ON pedigree_dog (kennelId)');
        $this->addSql('ALTER TABLE pedigree_dog RENAME INDEX idx_a91879963296987a TO IDX_13E11FFB3296987A');
        $this->addSql('ALTER TABLE pedigree_dog RENAME INDEX idx_a91879964c18fb21 TO IDX_13E11FFB4C18FB21');
        $this->addSql('ALTER TABLE pedigree_dog RENAME INDEX idx_a9187996ee8ea8d2 TO IDX_13E11FFBEE8EA8D2');
        $this->addSql('ALTER TABLE pedigree_dog RENAME INDEX idx_a9187996261bf88 TO IDX_13E11FFB261BF88');
        $this->addSql('ALTER TABLE pedigree_dog_breeder DROP FOREIGN KEY FK_53F8998458056B5');
        $this->addSql('DROP INDEX IDX_53F8998458056B5 ON pedigree_dog_breeder');
        $this->addSql('ALTER TABLE pedigree_dog_breeder DROP PRIMARY KEY');
        $this->addSql('ALTER TABLE pedigree_dog_breeder CHANGE animalid dogId INT NOT NULL');
        $this->addSql('ALTER TABLE pedigree_dog_breeder ADD CONSTRAINT FK_CE5D1D49EF294D6D FOREIGN KEY (dogId) REFERENCES pedigree_dog (id) ON UPDATE NO ACTION ON DELETE NO ACTION');
        $this->addSql('CREATE INDEX IDX_CE5D1D49EF294D6D ON pedigree_dog_breeder (dogId)');
        $this->addSql('ALTER TABLE pedigree_dog_breeder ADD PRIMARY KEY (dogId, breederId)');
        $this->addSql('ALTER TABLE pedigree_dog_breeder RENAME INDEX idx_53f8998a705ed45 TO IDX_CE5D1D49A705ED45');
        $this->addSql('ALTER TABLE pedigree_dog_kinship CHANGE animal1id dog1Id INT NOT NULL, CHANGE animal2id dog2Id INT NOT NULL');
        $this->addSql('ALTER TABLE pedigree_dog_kinship RENAME INDEX idx_b0b5d005d3bfa33a TO IDX_7BD744D4D3BFA33A');
        $this->addSql('ALTER TABLE pedigree_dog_kinship RENAME INDEX idx_b0b5d005d1f91d63 TO IDX_7BD744D4D1F91D63');
        $this->addSql('ALTER TABLE pedigree_dog_owner DROP FOREIGN KEY FK_39A1649F458056B5');
        $this->addSql('DROP INDEX IDX_39A1649F458056B5 ON pedigree_dog_owner');
        $this->addSql('ALTER TABLE pedigree_dog_owner DROP PRIMARY KEY');
        $this->addSql('ALTER TABLE pedigree_dog_owner CHANGE animalid dogId INT NOT NULL');
        $this->addSql('ALTER TABLE pedigree_dog_owner ADD CONSTRAINT FK_A81ED81DEF294D6D FOREIGN KEY (dogId) REFERENCES pedigree_dog (id) ON UPDATE NO ACTION ON DELETE NO ACTION');
        $this->addSql('CREATE INDEX IDX_A81ED81DEF294D6D ON pedigree_dog_owner (dogId)');
        $this->addSql('ALTER TABLE pedigree_dog_owner ADD PRIMARY KEY (dogId, ownerId)');
        $this->addSql('ALTER TABLE pedigree_dog_owner RENAME INDEX idx_39a1649fe05efd25 TO IDX_A81ED81DE05EFD25');
        $this->addSql('ALTER VIEW pedigree_dog_statistics AS SELECT x.id AS dogId, c.covariance - 1 AS inbreedingCoefficient, AVG(COALESCE(a.covariance,b.covariance,0)) AS averageCovariance FROM pedigree_dog x JOIN pedigree_dog y LEFT JOIN pedigree_dog_kinship a ON (a.dog1Id = x.id AND a.dog2Id = y.id) LEFT JOIN pedigree_dog_kinship b ON (b.dog1Id = y.id AND b.dog2Id = x.id) LEFT JOIN pedigree_dog_kinship c ON (c.dog1Id = x.id AND c.dog2Id = x.id) GROUP BY x.id');
        $this->addSql('ALTER TABLE pedigree_image DROP FOREIGN KEY FK_7AE92CA2458056B5');
        $this->addSql('DROP INDEX IDX_7AE92CA2458056B5 ON pedigree_image');
        $this->addSql('ALTER TABLE pedigree_image CHANGE animalid dogId INT DEFAULT NULL');
        $this->addSql('ALTER TABLE pedigree_image ADD CONSTRAINT FK_7AE92CA2EF294D6D FOREIGN KEY (dogId) REFERENCES pedigree_dog (id) ON UPDATE NO ACTION ON DELETE NO ACTION');
        $this->addSql('CREATE INDEX IDX_7AE92CA2EF294D6D ON pedigree_image (dogId)');
    }
}
