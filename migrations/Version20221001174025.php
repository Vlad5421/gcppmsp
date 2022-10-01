<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20221001174025 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE card ADD filial_id INT NOT NULL');
        $this->addSql('ALTER TABLE card ADD service_id INT NOT NULL');
        $this->addSql('ALTER TABLE card ADD start INT NOT NULL');
        $this->addSql('ALTER TABLE card ADD end_time INT NOT NULL');
        $this->addSql('ALTER TABLE card ADD CONSTRAINT FK_161498D3299B2577 FOREIGN KEY (filial_id) REFERENCES filial (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE card ADD CONSTRAINT FK_161498D3ED5CA9E6 FOREIGN KEY (service_id) REFERENCES service (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('CREATE INDEX IDX_161498D3299B2577 ON card (filial_id)');
        $this->addSql('CREATE INDEX IDX_161498D3ED5CA9E6 ON card (service_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE SCHEMA public');
        $this->addSql('ALTER TABLE card DROP CONSTRAINT FK_161498D3299B2577');
        $this->addSql('ALTER TABLE card DROP CONSTRAINT FK_161498D3ED5CA9E6');
        $this->addSql('DROP INDEX IDX_161498D3299B2577');
        $this->addSql('DROP INDEX IDX_161498D3ED5CA9E6');
        $this->addSql('ALTER TABLE card DROP filial_id');
        $this->addSql('ALTER TABLE card DROP service_id');
        $this->addSql('ALTER TABLE card DROP start');
        $this->addSql('ALTER TABLE card DROP end_time');
    }
}
