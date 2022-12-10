<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20221207162404 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE SEQUENCE schedule_interval_id_seq INCREMENT BY 1 MINVALUE 1 START 1');
        $this->addSql('CREATE TABLE schedule_interval (id INT NOT NULL, schedule_id INT NOT NULL, day INT NOT NULL, start INT NOT NULL, end_time INT NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE INDEX IDX_A52D06FDA40BC2D5 ON schedule_interval (schedule_id)');
        $this->addSql('ALTER TABLE schedule_interval ADD CONSTRAINT FK_A52D06FDA40BC2D5 FOREIGN KEY (schedule_id) REFERENCES schedule (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE schedule DROP day');
        $this->addSql('ALTER TABLE schedule DROP start');
        $this->addSql('ALTER TABLE schedule DROP end_time');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE SCHEMA public');
        $this->addSql('DROP SEQUENCE schedule_interval_id_seq CASCADE');
        $this->addSql('ALTER TABLE schedule_interval DROP CONSTRAINT FK_A52D06FDA40BC2D5');
        $this->addSql('DROP TABLE schedule_interval');
        $this->addSql('ALTER TABLE schedule ADD day VARCHAR(255) NOT NULL');
        $this->addSql('ALTER TABLE schedule ADD start INT NOT NULL');
        $this->addSql('ALTER TABLE schedule ADD end_time INT NOT NULL');
    }
}
