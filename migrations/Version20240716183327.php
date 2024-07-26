<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20240716183327 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE SEQUENCE "user_id_seq" INCREMENT BY 1 MINVALUE 1 START 1');
        $this->addSql('CREATE TABLE "user" (id INT NOT NULL, login VARCHAR(180) NOT NULL, roles JSON NOT NULL, password VARCHAR(255) NOT NULL, active BOOLEAN NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_IDENTIFIER_LOGIN ON "user" (login)');
        $this->addSql('ALTER TABLE files ALTER size TYPE VARCHAR(255)');
        $this->addSql('ALTER TABLE processes ADD owner_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE processes ADD client_ip VARCHAR(255) DEFAULT NULL');
        $this->addSql('ALTER TABLE processes ADD CONSTRAINT FK_A4289E4C7E3C61F9 FOREIGN KEY (owner_id) REFERENCES "user" (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('CREATE INDEX IDX_A4289E4C7E3C61F9 ON processes (owner_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE SCHEMA public');
        $this->addSql('ALTER TABLE processes DROP CONSTRAINT FK_A4289E4C7E3C61F9');
        $this->addSql('DROP SEQUENCE "user_id_seq" CASCADE');
        $this->addSql('DROP TABLE "user"');
        $this->addSql('DROP INDEX IDX_A4289E4C7E3C61F9');
        $this->addSql('ALTER TABLE processes DROP owner_id');
        $this->addSql('ALTER TABLE processes DROP client_ip');
        $this->addSql('ALTER TABLE files ALTER size TYPE INT');
    }
}
