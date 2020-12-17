<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20201217221306 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE articles DROP created_by');
        $this->addSql('ALTER TABLE articles DROP updated_by');
        $this->addSql('ALTER TABLE articles ALTER created_at DROP DEFAULT');
        $this->addSql('ALTER TABLE articles ALTER updated_at DROP DEFAULT');
        $this->addSql('ALTER TABLE likes DROP created_by');
        $this->addSql('ALTER TABLE likes DROP updated_by');
        $this->addSql('ALTER TABLE likes ALTER created_at DROP DEFAULT');
        $this->addSql('ALTER TABLE likes ALTER updated_at DROP DEFAULT');
        $this->addSql('ALTER TABLE notifications DROP created_by');
        $this->addSql('ALTER TABLE notifications DROP updated_by');
        $this->addSql('ALTER TABLE notifications ALTER created_at DROP DEFAULT');
        $this->addSql('ALTER TABLE notifications ALTER updated_at DROP DEFAULT');
        $this->addSql('ALTER TABLE sessions DROP created_by');
        $this->addSql('ALTER TABLE sessions DROP updated_by');
        $this->addSql('ALTER TABLE sessions ALTER created_at DROP DEFAULT');
        $this->addSql('ALTER TABLE sessions ALTER updated_at DROP DEFAULT');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE SCHEMA public');
        $this->addSql('ALTER TABLE articles ADD created_by INT DEFAULT NULL');
        $this->addSql('ALTER TABLE articles ADD updated_by INT DEFAULT NULL');
        $this->addSql('ALTER TABLE articles ALTER created_at SET DEFAULT CURRENT_TIMESTAMP');
        $this->addSql('ALTER TABLE articles ALTER updated_at SET DEFAULT CURRENT_TIMESTAMP');
        $this->addSql('ALTER TABLE likes ADD created_by INT DEFAULT NULL');
        $this->addSql('ALTER TABLE likes ADD updated_by INT DEFAULT NULL');
        $this->addSql('ALTER TABLE likes ALTER created_at SET DEFAULT CURRENT_TIMESTAMP');
        $this->addSql('ALTER TABLE likes ALTER updated_at SET DEFAULT CURRENT_TIMESTAMP');
        $this->addSql('ALTER TABLE sessions ADD created_by INT DEFAULT NULL');
        $this->addSql('ALTER TABLE sessions ADD updated_by INT DEFAULT NULL');
        $this->addSql('ALTER TABLE sessions ALTER created_at SET DEFAULT CURRENT_TIMESTAMP');
        $this->addSql('ALTER TABLE sessions ALTER updated_at SET DEFAULT CURRENT_TIMESTAMP');
        $this->addSql('ALTER TABLE notifications ADD created_by INT DEFAULT NULL');
        $this->addSql('ALTER TABLE notifications ADD updated_by INT DEFAULT NULL');
        $this->addSql('ALTER TABLE notifications ALTER created_at SET DEFAULT CURRENT_TIMESTAMP');
        $this->addSql('ALTER TABLE notifications ALTER updated_at SET DEFAULT CURRENT_TIMESTAMP');
    }
}
