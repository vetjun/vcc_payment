<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20191016123957 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'sqlite', 'Migration can only be executed safely on \'sqlite\'.');

        $this->addSql('CREATE TEMPORARY TABLE __temp__bucket AS SELECT id, start_date, end_date, limit_val, vendor FROM bucket');
        $this->addSql('DROP TABLE bucket');
        $this->addSql('CREATE TABLE bucket (id INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL, limit_val DOUBLE PRECISION NOT NULL, vendor VARCHAR(32) NOT NULL COLLATE BINARY, start_date DATETIME NOT NULL, end_date DATETIME NOT NULL)');
        $this->addSql('INSERT INTO bucket (id, start_date, end_date, limit_val, vendor) SELECT id, start_date, end_date, limit_val, vendor FROM __temp__bucket');
        $this->addSql('DROP TABLE __temp__bucket');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'sqlite', 'Migration can only be executed safely on \'sqlite\'.');

        $this->addSql('CREATE TEMPORARY TABLE __temp__bucket AS SELECT id, start_date, end_date, limit_val, vendor FROM bucket');
        $this->addSql('DROP TABLE bucket');
        $this->addSql('CREATE TABLE bucket (id INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL, limit_val DOUBLE PRECISION NOT NULL, vendor VARCHAR(32) NOT NULL, start_date DATE NOT NULL, end_date DATE NOT NULL)');
        $this->addSql('INSERT INTO bucket (id, start_date, end_date, limit_val, vendor) SELECT id, start_date, end_date, limit_val, vendor FROM __temp__bucket');
        $this->addSql('DROP TABLE __temp__bucket');
    }
}
