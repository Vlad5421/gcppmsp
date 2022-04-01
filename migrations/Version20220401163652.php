<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20220401163652 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE SEQUENCE complect_id_seq INCREMENT BY 1 MINVALUE 1 START 1');
        $this->addSql('CREATE TABLE complect (id INT NOT NULL, filial_id INT NOT NULL, service_id INT NOT NULL, schedule_id INT NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE INDEX IDX_2D547447299B2577 ON complect (filial_id)');
        $this->addSql('CREATE INDEX IDX_2D547447ED5CA9E6 ON complect (service_id)');
        $this->addSql('CREATE INDEX IDX_2D547447A40BC2D5 ON complect (schedule_id)');
        $this->addSql('ALTER TABLE complect ADD CONSTRAINT FK_2D547447299B2577 FOREIGN KEY (filial_id) REFERENCES filial (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE complect ADD CONSTRAINT FK_2D547447ED5CA9E6 FOREIGN KEY (service_id) REFERENCES service (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE complect ADD CONSTRAINT FK_2D547447A40BC2D5 FOREIGN KEY (schedule_id) REFERENCES schedule (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE SCHEMA public');
        $this->addSql('DROP SEQUENCE complect_id_seq CASCADE');
        $this->addSql('DROP TABLE complect');
    }
}
