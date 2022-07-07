<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20220707165716 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE SEQUENCE user_complect_reference_id_seq INCREMENT BY 1 MINVALUE 1 START 1');
        $this->addSql('CREATE TABLE user_complect_reference (id INT NOT NULL, worker_id INT NOT NULL, complect_id INT NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE INDEX IDX_E15425956B20BA36 ON user_complect_reference (worker_id)');
        $this->addSql('CREATE INDEX IDX_E154259534C17747 ON user_complect_reference (complect_id)');
        $this->addSql('ALTER TABLE user_complect_reference ADD CONSTRAINT FK_E15425956B20BA36 FOREIGN KEY (worker_id) REFERENCES "user" (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE user_complect_reference ADD CONSTRAINT FK_E154259534C17747 FOREIGN KEY (complect_id) REFERENCES complect (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE SCHEMA public');
        $this->addSql('DROP SEQUENCE user_complect_reference_id_seq CASCADE');
        $this->addSql('DROP TABLE user_complect_reference');
    }
}
