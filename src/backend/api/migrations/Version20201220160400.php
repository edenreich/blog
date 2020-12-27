<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20201220160400 extends AbstractMigration
{
    public function getDescription(): string
    {
        return 'Rename likes table to reactions.';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE reactions (id UUID NOT NULL, article_id UUID DEFAULT NULL, session_id UUID DEFAULT NULL, type VARCHAR(10) NOT NULL, created_at TIMESTAMP(0) WITH TIME ZONE DEFAULT NULL, updated_at TIMESTAMP(0) WITH TIME ZONE DEFAULT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE INDEX IDX_38737FB37294869C ON reactions (article_id)');
        $this->addSql('CREATE INDEX IDX_38737FB3613FECDF ON reactions (session_id)');
        $this->addSql('CREATE UNIQUE INDEX reactions_id_unique ON reactions (id)');
        $this->addSql('COMMENT ON COLUMN reactions.id IS \'(DC2Type:uuid)\'');
        $this->addSql('COMMENT ON COLUMN reactions.article_id IS \'(DC2Type:uuid)\'');
        $this->addSql('COMMENT ON COLUMN reactions.session_id IS \'(DC2Type:uuid)\'');
        $this->addSql('ALTER TABLE reactions ADD CONSTRAINT FK_38737FB37294869C FOREIGN KEY (article_id) REFERENCES articles (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE reactions ADD CONSTRAINT FK_38737FB3613FECDF FOREIGN KEY (session_id) REFERENCES sessions (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('DROP TABLE likes');
        $this->addSql('ALTER TABLE articles ALTER created_at SET NOT NULL');
        $this->addSql('ALTER TABLE sessions ALTER created_at SET NOT NULL');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE SCHEMA public');
        $this->addSql('CREATE TABLE likes (id UUID NOT NULL, article_id UUID DEFAULT NULL, session_id UUID DEFAULT NULL, reaction_type VARCHAR(10) NOT NULL, created_at TIMESTAMP(0) WITH TIME ZONE DEFAULT NULL, updated_at TIMESTAMP(0) WITH TIME ZONE DEFAULT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE INDEX idx_49ca4e7d7294869c ON likes (article_id)');
        $this->addSql('CREATE UNIQUE INDEX likes_id_unique ON likes (id)');
        $this->addSql('CREATE INDEX idx_49ca4e7d613fecdf ON likes (session_id)');
        $this->addSql('COMMENT ON COLUMN likes.id IS \'(DC2Type:uuid)\'');
        $this->addSql('COMMENT ON COLUMN likes.article_id IS \'(DC2Type:uuid)\'');
        $this->addSql('COMMENT ON COLUMN likes.session_id IS \'(DC2Type:uuid)\'');
        $this->addSql('ALTER TABLE likes ADD CONSTRAINT fk_49ca4e7d7294869c FOREIGN KEY (article_id) REFERENCES articles (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE likes ADD CONSTRAINT fk_49ca4e7d613fecdf FOREIGN KEY (session_id) REFERENCES sessions (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('DROP TABLE reactions');
        $this->addSql('ALTER TABLE articles ALTER created_at DROP NOT NULL');
        $this->addSql('ALTER TABLE sessions ALTER created_at DROP NOT NULL');
    }
}
