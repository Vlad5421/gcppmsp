<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20220501120434 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE SEQUENCE schedule_complect_id_seq INCREMENT BY 1 MINVALUE 1 START 1');
        $this->addSql('CREATE TABLE schedule_complect (id INT NOT NULL, schedule_id INT NOT NULL, session_id INT NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE INDEX IDX_91BF9D71A40BC2D5 ON schedule_complect (schedule_id)');
        $this->addSql('CREATE INDEX IDX_91BF9D71613FECDF ON schedule_complect (session_id)');
        $this->addSql('ALTER TABLE schedule_complect ADD CONSTRAINT FK_91BF9D71A40BC2D5 FOREIGN KEY (schedule_id) REFERENCES schedule (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE schedule_complect ADD CONSTRAINT FK_91BF9D71613FECDF FOREIGN KEY (session_id) REFERENCES session (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE session DROP CONSTRAINT fk_d044d5d4a40bc2d5');
        $this->addSql('DROP INDEX idx_d044d5d4a40bc2d5');
        $this->addSql('ALTER TABLE session DROP schedule_id');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE SCHEMA public');
        $this->addSql('DROP SEQUENCE schedule_complect_id_seq CASCADE');
        $this->addSql('DROP TABLE schedule_complect');
        $this->addSql('ALTER TABLE session ADD schedule_id INT NOT NULL');
        $this->addSql('ALTER TABLE session ADD CONSTRAINT fk_d044d5d4a40bc2d5 FOREIGN KEY (schedule_id) REFERENCES schedule (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('CREATE INDEX idx_d044d5d4a40bc2d5 ON session (schedule_id)');
    }
}
