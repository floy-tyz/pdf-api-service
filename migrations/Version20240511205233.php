<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20240511205233 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE SEQUENCE conversions_id_seq INCREMENT BY 1 MINVALUE 1 START 1');
        $this->addSql('CREATE SEQUENCE files_id_seq INCREMENT BY 1 MINVALUE 1 START 1');
        $this->addSql('CREATE TABLE conversions (id INT NOT NULL, uuid UUID NOT NULL, created_at TIMESTAMP(0) WITHOUT TIME ZONE NOT NULL, updated_at TIMESTAMP(0) WITHOUT TIME ZONE NOT NULL, PRIMARY KEY(id))');
        $this->addSql('COMMENT ON COLUMN conversions.uuid IS \'(DC2Type:uuid)\'');
        $this->addSql('CREATE TABLE files (id INT NOT NULL, conversion_id INT NOT NULL, path VARCHAR(255) NOT NULL, original_file_name VARCHAR(255) NOT NULL, uuid_file_name UUID NOT NULL, extension VARCHAR(255) NOT NULL, size INT NOT NULL, mime_type VARCHAR(255) DEFAULT NULL, "order" INT DEFAULT NULL, is_used BOOLEAN DEFAULT false NOT NULL, created_at TIMESTAMP(0) WITHOUT TIME ZONE NOT NULL, updated_at TIMESTAMP(0) WITHOUT TIME ZONE NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE INDEX IDX_63540594C1FF126 ON files (conversion_id)');
        $this->addSql('COMMENT ON COLUMN files.uuid_file_name IS \'(DC2Type:uuid)\'');
        $this->addSql('ALTER TABLE files ADD CONSTRAINT FK_63540594C1FF126 FOREIGN KEY (conversion_id) REFERENCES conversions (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE SCHEMA public');
        $this->addSql('DROP SEQUENCE conversions_id_seq CASCADE');
        $this->addSql('DROP SEQUENCE files_id_seq CASCADE');
        $this->addSql('ALTER TABLE files DROP CONSTRAINT FK_63540594C1FF126');
        $this->addSql('DROP TABLE conversions');
        $this->addSql('DROP TABLE files');
    }
}
