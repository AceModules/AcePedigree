<?php

declare(strict_types=1);

namespace AcePedigree\Migrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20190919071537 extends AbstractMigration
{
    public function getDescription() : string
    {
        return 'Create view for mean kinship coefficient.';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('CREATE VIEW pedigree_kinship_avg AS SELECT x.id AS dogId, AVG(COALESCE(a.covariance,b.covariance,0)) AS avgCovariance FROM pedigree_dog x JOIN pedigree_dog y LEFT JOIN pedigree_kinship a ON (a.dog1Id = x.id AND a.dog2Id = y.id) LEFT JOIN pedigree_kinship b ON (b.dog1Id = y.id AND b.dog2Id = x.id) GROUP BY x.id');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('DROP VIEW pedigree_kinship_avg');
    }
}
