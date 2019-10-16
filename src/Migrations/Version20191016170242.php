<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20191016170242 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'sqlite', 'Migration can only be executed safely on \'sqlite\'.');

        $this->addSql('CREATE TEMPORARY TABLE __temp__vcc_vendor AS SELECT id, process_id, activation_date, expire_date, balance, currency, notes, reference, card_number, vendor, cvc FROM vcc_vendor');
        $this->addSql('DROP TABLE vcc_vendor');
        $this->addSql('CREATE TABLE vcc_vendor (id INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL, activation_date DATETIME NOT NULL, expire_date DATETIME NOT NULL, balance DOUBLE PRECISION NOT NULL, currency VARCHAR(5) NOT NULL COLLATE BINARY, notes VARCHAR(255) DEFAULT NULL COLLATE BINARY, reference VARCHAR(255) NOT NULL COLLATE BINARY, card_number VARCHAR(16) NOT NULL COLLATE BINARY, vendor VARCHAR(32) NOT NULL COLLATE BINARY, cvc INTEGER NOT NULL, process_id VARCHAR(255) NOT NULL)');
        $this->addSql('INSERT INTO vcc_vendor (id, process_id, activation_date, expire_date, balance, currency, notes, reference, card_number, vendor, cvc) SELECT id, process_id, activation_date, expire_date, balance, currency, notes, reference, card_number, vendor, cvc FROM __temp__vcc_vendor');
        $this->addSql('DROP TABLE __temp__vcc_vendor');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'sqlite', 'Migration can only be executed safely on \'sqlite\'.');

        $this->addSql('CREATE TEMPORARY TABLE __temp__vcc_vendor AS SELECT id, process_id, activation_date, expire_date, balance, currency, notes, reference, card_number, vendor, cvc FROM vcc_vendor');
        $this->addSql('DROP TABLE vcc_vendor');
        $this->addSql('CREATE TABLE vcc_vendor (id INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL, activation_date DATETIME NOT NULL, expire_date DATETIME NOT NULL, balance DOUBLE PRECISION NOT NULL, currency VARCHAR(5) NOT NULL, notes VARCHAR(255) DEFAULT NULL, reference VARCHAR(255) NOT NULL, card_number VARCHAR(16) NOT NULL, vendor VARCHAR(32) NOT NULL, cvc INTEGER NOT NULL, process_id INTEGER NOT NULL)');
        $this->addSql('INSERT INTO vcc_vendor (id, process_id, activation_date, expire_date, balance, currency, notes, reference, card_number, vendor, cvc) SELECT id, process_id, activation_date, expire_date, balance, currency, notes, reference, card_number, vendor, cvc FROM __temp__vcc_vendor');
        $this->addSql('DROP TABLE __temp__vcc_vendor');
    }
}
