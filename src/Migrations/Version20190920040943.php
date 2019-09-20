<?php

declare(strict_types=1);

namespace AcePedigree\Migrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20190920040943 extends AbstractMigration
{
    public function getDescription() : string
    {
        return 'Rename kinship tables to add dog prefix.';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('DROP VIEW pedigree_kinship_avg');
        $this->addSql('RENAME TABLE pedigree_kinship TO pedigree_dog_kinship');
        $this->addSql('ALTER TABLE pedigree_dog_kinship RENAME INDEX IDX_52F7EC39D1F91D63 TO IDX_7BD744D4D1F91D63');
        $this->addSql('ALTER TABLE pedigree_dog_kinship RENAME INDEX IDX_52F7EC39D3BFA33A TO IDX_7BD744D4D3BFA33A');
        $this->addSql('CREATE VIEW pedigree_dog_statistics AS SELECT x.id AS dogId, c.covariance - 1 AS inbreedingCoefficient, AVG(COALESCE(a.covariance,b.covariance,0)) AS averageCovariance FROM pedigree_dog x JOIN pedigree_dog y LEFT JOIN pedigree_dog_kinship a ON (a.dog1Id = x.id AND a.dog2Id = y.id) LEFT JOIN pedigree_dog_kinship b ON (b.dog1Id = y.id AND b.dog2Id = x.id) LEFT JOIN pedigree_dog_kinship c ON (c.dog1ID = x.id AND c.dog2Id = x.id) GROUP BY x.id');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('DROP VIEW pedigree_dog_statistics');
        $this->addSql('RENAME TABLE pedigree_dog_kinship TO pedigree_kinship');
        $this->addSql('ALTER TABLE pedigree_kinship RENAME INDEX IDX_7BD744D4D3BFA33A TO IDX_52F7EC39D3BFA33A');
        $this->addSql('ALTER TABLE pedigree_kinship RENAME INDEX IDX_7BD744D4D1F91D63 TO IDX_52F7EC39D1F91D63');
        $this->addSql('CREATE VIEW pedigree_kinship_avg AS SELECT x.id AS dogId, AVG(COALESCE(a.covariance,b.covariance,0)) AS avgCovariance FROM pedigree_dog x JOIN pedigree_dog y LEFT JOIN pedigree_kinship a ON (a.dog1Id = x.id AND a.dog2Id = y.id) LEFT JOIN pedigree_kinship b ON (b.dog1Id = y.id AND b.dog2Id = x.id) GROUP BY x.id');
    }
}
