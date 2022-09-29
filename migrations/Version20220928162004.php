<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20220928162004 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE SEQUENCE user_service_id_seq INCREMENT BY 1 MINVALUE 1 START 1');
        $this->addSql('CREATE TABLE user_service (id INT NOT NULL, worker_id INT NOT NULL, service_id INT NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE INDEX IDX_B99084D86B20BA36 ON user_service (worker_id)');
        $this->addSql('CREATE INDEX IDX_B99084D8ED5CA9E6 ON user_service (service_id)');
        $this->addSql('ALTER TABLE user_service ADD CONSTRAINT FK_B99084D86B20BA36 FOREIGN KEY (worker_id) REFERENCES "user" (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE user_service ADD CONSTRAINT FK_B99084D8ED5CA9E6 FOREIGN KEY (service_id) REFERENCES service (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE SCHEMA public');
        $this->addSql('DROP SEQUENCE user_service_id_seq CASCADE');
        $this->addSql('ALTER TABLE user_service DROP CONSTRAINT FK_B99084D86B20BA36');
        $this->addSql('ALTER TABLE user_service DROP CONSTRAINT FK_B99084D8ED5CA9E6');
        $this->addSql('DROP TABLE user_service');
    }
}
