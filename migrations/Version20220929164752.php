<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20220929164752 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE schedule ADD filial_id INT NOT NULL');
        $this->addSql('ALTER TABLE schedule ADD CONSTRAINT FK_5A3811FB299B2577 FOREIGN KEY (filial_id) REFERENCES filial (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('CREATE INDEX IDX_5A3811FB299B2577 ON schedule (filial_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE SCHEMA public');
        $this->addSql('ALTER TABLE schedule DROP CONSTRAINT FK_5A3811FB299B2577');
        $this->addSql('DROP INDEX IDX_5A3811FB299B2577');
        $this->addSql('ALTER TABLE schedule DROP filial_id');
    }
}
