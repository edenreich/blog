<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20210207225152 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE navigations (id UUID NOT NULL, parent_id UUID DEFAULT NULL, name VARCHAR(180) NOT NULL, url VARCHAR(180) NOT NULL, icon VARCHAR(180) NOT NULL, updated_at TIMESTAMP(0) WITH TIME ZONE DEFAULT NULL, created_at TIMESTAMP(0) WITH TIME ZONE NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_AD21D8F35E237E06 ON navigations (name)');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_AD21D8F3F47645AE ON navigations (url)');
        $this->addSql('CREATE INDEX IDX_AD21D8F3727ACA70 ON navigations (parent_id)');
        $this->addSql('COMMENT ON COLUMN navigations.id IS \'(DC2Type:uuid)\'');
        $this->addSql('COMMENT ON COLUMN navigations.parent_id IS \'(DC2Type:uuid)\'');
        $this->addSql('ALTER TABLE navigations ADD CONSTRAINT FK_AD21D8F3727ACA70 FOREIGN KEY (parent_id) REFERENCES navigations (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE SCHEMA public');
        $this->addSql('ALTER TABLE navigations DROP CONSTRAINT FK_AD21D8F3727ACA70');
        $this->addSql('DROP TABLE navigations');
    }
}
