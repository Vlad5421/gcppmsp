<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20221001172851 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE card DROP CONSTRAINT fk_161498d334c17747');
        $this->addSql('ALTER TABLE card DROP CONSTRAINT fk_161498d3613fecdf');
        $this->addSql('DROP INDEX idx_161498d3613fecdf');
        $this->addSql('DROP INDEX idx_161498d334c17747');
        $this->addSql('ALTER TABLE card DROP complect_id');
        $this->addSql('ALTER TABLE card DROP session_id');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE SCHEMA public');
        $this->addSql('ALTER TABLE card ADD complect_id INT NOT NULL');
        $this->addSql('ALTER TABLE card ADD session_id INT NOT NULL');
        $this->addSql('ALTER TABLE card ADD CONSTRAINT fk_161498d334c17747 FOREIGN KEY (complect_id) REFERENCES complect (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE card ADD CONSTRAINT fk_161498d3613fecdf FOREIGN KEY (session_id) REFERENCES session (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('CREATE INDEX idx_161498d3613fecdf ON card (session_id)');
        $this->addSql('CREATE INDEX idx_161498d334c17747 ON card (complect_id)');
    }
}
