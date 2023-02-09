<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20230209095824 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE SEQUENCE coordinate_id_seq INCREMENT BY 1 MINVALUE 1 START 1');
        $this->addSql('CREATE SEQUENCE driver_id_seq INCREMENT BY 1 MINVALUE 1 START 1');
        $this->addSql('CREATE SEQUENCE vehicle_id_seq INCREMENT BY 1 MINVALUE 1 START 1');
        $this->addSql('CREATE TABLE coordinate (id INT NOT NULL, driver_id INT DEFAULT NULL, latitude INT NOT NULL, longitude INT NOT NULL, updated_at TIMESTAMP(0) WITHOUT TIME ZONE NOT NULL, created_at TIMESTAMP(0) WITHOUT TIME ZONE NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE INDEX IDX_CB9CBA17C3423909 ON coordinate (driver_id)');
        $this->addSql('COMMENT ON COLUMN coordinate.updated_at IS \'(DC2Type:datetime_immutable)\'');
        $this->addSql('COMMENT ON COLUMN coordinate.created_at IS \'(DC2Type:datetime_immutable)\'');
        $this->addSql('ALTER TABLE coordinate ADD CONSTRAINT FK_CB9CBA17C3423909 FOREIGN KEY (driver_id) REFERENCES driver (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE SCHEMA public');
        $this->addSql('DROP SEQUENCE coordinate_id_seq CASCADE');
        $this->addSql('DROP SEQUENCE driver_id_seq CASCADE');
        $this->addSql('DROP SEQUENCE vehicle_id_seq CASCADE');
        $this->addSql('ALTER TABLE coordinate DROP CONSTRAINT FK_CB9CBA17C3423909');
        $this->addSql('DROP TABLE coordinate');
    }
}
