<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20220929130839 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE SEQUENCE filial_service_id_seq INCREMENT BY 1 MINVALUE 1 START 1');
        $this->addSql('CREATE TABLE filial_service (id INT NOT NULL, filial_id INT NOT NULL, service_id INT NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE INDEX IDX_5E865DE2299B2577 ON filial_service (filial_id)');
        $this->addSql('CREATE INDEX IDX_5E865DE2ED5CA9E6 ON filial_service (service_id)');
        $this->addSql('ALTER TABLE filial_service ADD CONSTRAINT FK_5E865DE2299B2577 FOREIGN KEY (filial_id) REFERENCES filial (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE filial_service ADD CONSTRAINT FK_5E865DE2ED5CA9E6 FOREIGN KEY (service_id) REFERENCES service (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE SCHEMA public');
        $this->addSql('DROP SEQUENCE filial_service_id_seq CASCADE');
        $this->addSql('ALTER TABLE filial_service DROP CONSTRAINT FK_5E865DE2299B2577');
        $this->addSql('ALTER TABLE filial_service DROP CONSTRAINT FK_5E865DE2ED5CA9E6');
        $this->addSql('DROP TABLE filial_service');
    }
}
