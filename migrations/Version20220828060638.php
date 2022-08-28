<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20220828060638 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE filial ADD collection_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE filial ADD CONSTRAINT FK_F5599759514956FD FOREIGN KEY (collection_id) REFERENCES collections (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('CREATE INDEX IDX_F5599759514956FD ON filial (collection_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE SCHEMA public');
        $this->addSql('ALTER TABLE filial DROP CONSTRAINT FK_F5599759514956FD');
        $this->addSql('DROP INDEX IDX_F5599759514956FD');
        $this->addSql('ALTER TABLE filial DROP collection_id');
    }
}
