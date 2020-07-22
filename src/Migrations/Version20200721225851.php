<?php

declare(strict_types=1);

namespace AcePedigree\Migrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20200721225851 extends AbstractMigration
{
    public function getDescription() : string
    {
        return 'Added column for relative pedigree popularity.';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE pedigree_animal ADD relativePopularity DOUBLE PRECISION DEFAULT NULL');
        $this->addSql('ALTER TABLE pedigree_animal_kinship ADD ancestorId INT DEFAULT NULL');
        $this->addSql('ALTER TABLE pedigree_animal_kinship ADD CONSTRAINT FK_B0B5D0051054AA12 FOREIGN KEY (ancestorId) REFERENCES pedigree_animal (id)');
        $this->addSql('CREATE INDEX IDX_B0B5D0051054AA12 ON pedigree_animal_kinship (ancestorId)');
        $this->addSql('ALTER VIEW pedigree_animal_statistics AS SELECT x.id AS animalId, c.covariance - 1 AS inbreedingCoefficient, AVG(COALESCE(a.covariance,b.covariance,0)) AS averageCovariance, SUM(IF(x.id IN (a.ancestorId, b.ancestorId),1,0)) / COUNT(y.id) AS relativePopularity FROM pedigree_animal x JOIN pedigree_animal y LEFT JOIN pedigree_animal_kinship a ON (a.animal1Id = x.id AND a.animal2Id = y.id) LEFT JOIN pedigree_animal_kinship b ON (b.animal1Id = y.id AND b.animal2Id = x.id) LEFT JOIN pedigree_animal_kinship c ON (c.animal1Id = x.id AND c.animal2Id = x.id) GROUP BY x.id');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE pedigree_animal DROP relativePopularity');
        $this->addSql('ALTER TABLE pedigree_animal_kinship DROP FOREIGN KEY FK_B0B5D0051054AA12');
        $this->addSql('DROP INDEX IDX_B0B5D0051054AA12 ON pedigree_animal_kinship');
        $this->addSql('ALTER TABLE pedigree_animal_kinship DROP ancestorId');
        $this->addSql('ALTER VIEW pedigree_animal_statistics AS SELECT x.id AS animalId, c.covariance - 1 AS inbreedingCoefficient, AVG(COALESCE(a.covariance,b.covariance,0)) AS averageCovariance FROM pedigree_animal x JOIN pedigree_animal y LEFT JOIN pedigree_animal_kinship a ON (a.animal1Id = x.id AND a.animal2Id = y.id) LEFT JOIN pedigree_animal_kinship b ON (b.animal1Id = y.id AND b.animal2Id = x.id) LEFT JOIN pedigree_animal_kinship c ON (c.animal1Id = x.id AND c.animal2Id = x.id) GROUP BY x.id');
    }
}
