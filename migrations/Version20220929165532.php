<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20220929165532 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE schedule ADD worker_id INT NOT NULL');
        $this->addSql('ALTER TABLE schedule ADD day VARCHAR(255) NOT NULL');
        $this->addSql('ALTER TABLE schedule ADD start INT NOT NULL');
        $this->addSql('ALTER TABLE schedule ADD end_time INT NOT NULL');
        $this->addSql('ALTER TABLE schedule ADD CONSTRAINT FK_5A3811FB6B20BA36 FOREIGN KEY (worker_id) REFERENCES "user" (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('CREATE INDEX IDX_5A3811FB6B20BA36 ON schedule (worker_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE SCHEMA public');
        $this->addSql('ALTER TABLE schedule DROP CONSTRAINT FK_5A3811FB6B20BA36');
        $this->addSql('DROP INDEX IDX_5A3811FB6B20BA36');
        $this->addSql('ALTER TABLE schedule DROP worker_id');
        $this->addSql('ALTER TABLE schedule DROP day');
        $this->addSql('ALTER TABLE schedule DROP start');
        $this->addSql('ALTER TABLE schedule DROP end_time');
    }
}
