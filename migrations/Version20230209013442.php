<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20230209013442 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE SEQUENCE driver_id_seq INCREMENT BY 1 MINVALUE 1 START 1');
        $this->addSql('CREATE SEQUENCE vehicle_id_seq INCREMENT BY 1 MINVALUE 1 START 1');
        $this->addSql('CREATE TABLE driver (id INT NOT NULL, vehicle_id INT NOT NULL, name VARCHAR(255) NOT NULL, document VARCHAR(14) NOT NULL, updated_at TIMESTAMP(0) WITHOUT TIME ZONE NOT NULL, created_at TIMESTAMP(0) WITHOUT TIME ZONE NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_11667CD9D8698A76 ON driver (document)');
        $this->addSql('CREATE INDEX IDX_11667CD9545317D1 ON driver (vehicle_id)');
        $this->addSql('COMMENT ON COLUMN driver.updated_at IS \'(DC2Type:datetime_immutable)\'');
        $this->addSql('COMMENT ON COLUMN driver.created_at IS \'(DC2Type:datetime_immutable)\'');
        $this->addSql('CREATE TABLE vehicle (id INT NOT NULL, name VARCHAR(255) NOT NULL, plate VARCHAR(7) NOT NULL, color VARCHAR(255) DEFAULT NULL, updated_at TIMESTAMP(0) WITHOUT TIME ZONE NOT NULL, created_at TIMESTAMP(0) WITHOUT TIME ZONE NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_1B80E486719ED75B ON vehicle (plate)');
        $this->addSql('COMMENT ON COLUMN vehicle.updated_at IS \'(DC2Type:datetime_immutable)\'');
        $this->addSql('COMMENT ON COLUMN vehicle.created_at IS \'(DC2Type:datetime_immutable)\'');
        $this->addSql('ALTER TABLE driver ADD CONSTRAINT FK_11667CD9545317D1 FOREIGN KEY (vehicle_id) REFERENCES vehicle (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE SCHEMA public');
        $this->addSql('DROP SEQUENCE driver_id_seq CASCADE');
        $this->addSql('DROP SEQUENCE vehicle_id_seq CASCADE');
        $this->addSql('ALTER TABLE driver DROP CONSTRAINT FK_11667CD9545317D1');
        $this->addSql('DROP TABLE driver');
        $this->addSql('DROP TABLE vehicle');
    }
}
