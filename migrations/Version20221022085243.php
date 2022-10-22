<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20221022085243 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE SEQUENCE card_id_seq INCREMENT BY 1 MINVALUE 1 START 1');
        $this->addSql('CREATE SEQUENCE collections_id_seq INCREMENT BY 1 MINVALUE 1 START 1');
        $this->addSql('CREATE SEQUENCE ext_log_entries_id_seq INCREMENT BY 1 MINVALUE 1 START 1');
        $this->addSql('CREATE SEQUENCE filial_id_seq INCREMENT BY 1 MINVALUE 1 START 1');
        $this->addSql('CREATE SEQUENCE filial_service_id_seq INCREMENT BY 1 MINVALUE 1 START 1');
        $this->addSql('CREATE SEQUENCE schedule_id_seq INCREMENT BY 1 MINVALUE 1 START 1');
        $this->addSql('CREATE SEQUENCE service_id_seq INCREMENT BY 1 MINVALUE 1 START 1');
        $this->addSql('CREATE SEQUENCE "user_id_seq" INCREMENT BY 1 MINVALUE 1 START 1');
        $this->addSql('CREATE SEQUENCE user_service_id_seq INCREMENT BY 1 MINVALUE 1 START 1');
        $this->addSql('CREATE SEQUENCE visitor_id_seq INCREMENT BY 1 MINVALUE 1 START 1');
        $this->addSql('CREATE TABLE card (id INT NOT NULL, specialist_id INT NOT NULL, filial_id INT NOT NULL, service_id INT NOT NULL, date DATE DEFAULT NULL, start INT NOT NULL, end_time INT NOT NULL, created_at TIMESTAMP(0) WITHOUT TIME ZONE NOT NULL, updated_at TIMESTAMP(0) WITHOUT TIME ZONE NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE INDEX IDX_161498D37B100C1A ON card (specialist_id)');
        $this->addSql('CREATE INDEX IDX_161498D3299B2577 ON card (filial_id)');
        $this->addSql('CREATE INDEX IDX_161498D3ED5CA9E6 ON card (service_id)');
        $this->addSql('CREATE TABLE collections (id INT NOT NULL, name VARCHAR(255) NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE TABLE ext_log_entries (id INT NOT NULL, action VARCHAR(8) NOT NULL, logged_at TIMESTAMP(0) WITHOUT TIME ZONE NOT NULL, object_id VARCHAR(64) DEFAULT NULL, object_class VARCHAR(191) NOT NULL, version INT NOT NULL, data TEXT DEFAULT NULL, username VARCHAR(191) DEFAULT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE INDEX log_class_lookup_idx ON ext_log_entries (object_class)');
        $this->addSql('CREATE INDEX log_date_lookup_idx ON ext_log_entries (logged_at)');
        $this->addSql('CREATE INDEX log_user_lookup_idx ON ext_log_entries (username)');
        $this->addSql('CREATE INDEX log_version_lookup_idx ON ext_log_entries (object_id, object_class, version)');
        $this->addSql('COMMENT ON COLUMN ext_log_entries.data IS \'(DC2Type:array)\'');
        $this->addSql('CREATE TABLE ext_translations (id SERIAL NOT NULL, locale VARCHAR(8) NOT NULL, object_class VARCHAR(191) NOT NULL, field VARCHAR(32) NOT NULL, foreign_key VARCHAR(64) NOT NULL, content TEXT DEFAULT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE INDEX translations_lookup_idx ON ext_translations (locale, object_class, foreign_key)');
        $this->addSql('CREATE INDEX general_translations_lookup_idx ON ext_translations (object_class, foreign_key)');
        $this->addSql('CREATE UNIQUE INDEX lookup_unique_idx ON ext_translations (locale, object_class, field, foreign_key)');
        $this->addSql('CREATE TABLE filial (id INT NOT NULL, collection_id INT DEFAULT NULL, name VARCHAR(255) NOT NULL, address VARCHAR(255) NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE INDEX IDX_F5599759514956FD ON filial (collection_id)');
        $this->addSql('CREATE TABLE filial_service (id INT NOT NULL, filial_id INT NOT NULL, service_id INT NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE INDEX IDX_5E865DE2299B2577 ON filial_service (filial_id)');
        $this->addSql('CREATE INDEX IDX_5E865DE2ED5CA9E6 ON filial_service (service_id)');
        $this->addSql('CREATE TABLE schedule (id INT NOT NULL, filial_id INT NOT NULL, worker_id INT NOT NULL, name VARCHAR(255) NOT NULL, day VARCHAR(255) NOT NULL, start INT NOT NULL, end_time INT NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE INDEX IDX_5A3811FB299B2577 ON schedule (filial_id)');
        $this->addSql('CREATE INDEX IDX_5A3811FB6B20BA36 ON schedule (worker_id)');
        $this->addSql('CREATE TABLE service (id INT NOT NULL, name VARCHAR(255) NOT NULL, price VARCHAR(255) NOT NULL, duration INT NOT NULL, service_logo VARCHAR(255) DEFAULT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE TABLE "user" (id INT NOT NULL, fio VARCHAR(255) NOT NULL, email VARCHAR(255) NOT NULL, password VARCHAR(255) NOT NULL, roles JSON NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE TABLE user_service (id INT NOT NULL, worker_id INT NOT NULL, service_id INT NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE INDEX IDX_B99084D86B20BA36 ON user_service (worker_id)');
        $this->addSql('CREATE INDEX IDX_B99084D8ED5CA9E6 ON user_service (service_id)');
        $this->addSql('CREATE TABLE visitor (id INT NOT NULL, card_id INT NOT NULL, name VARCHAR(255) NOT NULL, email VARCHAR(255) NOT NULL, phone_number VARCHAR(255) NOT NULL, age_children VARCHAR(2) NOT NULL, reason TEXT DEFAULT NULL, consult_form VARCHAR(255) NOT NULL, consent BOOLEAN NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE INDEX IDX_CAE5E19F4ACC9A20 ON visitor (card_id)');
        $this->addSql('ALTER TABLE card ADD CONSTRAINT FK_161498D37B100C1A FOREIGN KEY (specialist_id) REFERENCES "user" (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE card ADD CONSTRAINT FK_161498D3299B2577 FOREIGN KEY (filial_id) REFERENCES filial (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE card ADD CONSTRAINT FK_161498D3ED5CA9E6 FOREIGN KEY (service_id) REFERENCES service (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE filial ADD CONSTRAINT FK_F5599759514956FD FOREIGN KEY (collection_id) REFERENCES collections (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE filial_service ADD CONSTRAINT FK_5E865DE2299B2577 FOREIGN KEY (filial_id) REFERENCES filial (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE filial_service ADD CONSTRAINT FK_5E865DE2ED5CA9E6 FOREIGN KEY (service_id) REFERENCES service (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE schedule ADD CONSTRAINT FK_5A3811FB299B2577 FOREIGN KEY (filial_id) REFERENCES filial (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE schedule ADD CONSTRAINT FK_5A3811FB6B20BA36 FOREIGN KEY (worker_id) REFERENCES "user" (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE user_service ADD CONSTRAINT FK_B99084D86B20BA36 FOREIGN KEY (worker_id) REFERENCES "user" (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE user_service ADD CONSTRAINT FK_B99084D8ED5CA9E6 FOREIGN KEY (service_id) REFERENCES service (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE visitor ADD CONSTRAINT FK_CAE5E19F4ACC9A20 FOREIGN KEY (card_id) REFERENCES card (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE SCHEMA public');
        $this->addSql('DROP SEQUENCE card_id_seq CASCADE');
        $this->addSql('DROP SEQUENCE collections_id_seq CASCADE');
        $this->addSql('DROP SEQUENCE ext_log_entries_id_seq CASCADE');
        $this->addSql('DROP SEQUENCE filial_id_seq CASCADE');
        $this->addSql('DROP SEQUENCE filial_service_id_seq CASCADE');
        $this->addSql('DROP SEQUENCE schedule_id_seq CASCADE');
        $this->addSql('DROP SEQUENCE service_id_seq CASCADE');
        $this->addSql('DROP SEQUENCE "user_id_seq" CASCADE');
        $this->addSql('DROP SEQUENCE user_service_id_seq CASCADE');
        $this->addSql('DROP SEQUENCE visitor_id_seq CASCADE');
        $this->addSql('ALTER TABLE card DROP CONSTRAINT FK_161498D37B100C1A');
        $this->addSql('ALTER TABLE card DROP CONSTRAINT FK_161498D3299B2577');
        $this->addSql('ALTER TABLE card DROP CONSTRAINT FK_161498D3ED5CA9E6');
        $this->addSql('ALTER TABLE filial DROP CONSTRAINT FK_F5599759514956FD');
        $this->addSql('ALTER TABLE filial_service DROP CONSTRAINT FK_5E865DE2299B2577');
        $this->addSql('ALTER TABLE filial_service DROP CONSTRAINT FK_5E865DE2ED5CA9E6');
        $this->addSql('ALTER TABLE schedule DROP CONSTRAINT FK_5A3811FB299B2577');
        $this->addSql('ALTER TABLE schedule DROP CONSTRAINT FK_5A3811FB6B20BA36');
        $this->addSql('ALTER TABLE user_service DROP CONSTRAINT FK_B99084D86B20BA36');
        $this->addSql('ALTER TABLE user_service DROP CONSTRAINT FK_B99084D8ED5CA9E6');
        $this->addSql('ALTER TABLE visitor DROP CONSTRAINT FK_CAE5E19F4ACC9A20');
        $this->addSql('DROP TABLE card');
        $this->addSql('DROP TABLE collections');
        $this->addSql('DROP TABLE ext_log_entries');
        $this->addSql('DROP TABLE ext_translations');
        $this->addSql('DROP TABLE filial');
        $this->addSql('DROP TABLE filial_service');
        $this->addSql('DROP TABLE schedule');
        $this->addSql('DROP TABLE service');
        $this->addSql('DROP TABLE "user"');
        $this->addSql('DROP TABLE user_service');
        $this->addSql('DROP TABLE visitor');
    }
}
