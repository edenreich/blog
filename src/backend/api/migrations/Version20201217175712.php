<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20201217175712 extends AbstractMigration
{
    public function getDescription() : string
    {
        return 'Use uuid and attach necessary relations.';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE articles ALTER id TYPE UUID');
        $this->addSql('ALTER TABLE articles ALTER id DROP DEFAULT');
        $this->addSql('COMMENT ON COLUMN articles.id IS \'(DC2Type:uuid)\'');
        $this->addSql('ALTER TABLE likes ADD article_id UUID DEFAULT NULL');
        $this->addSql('ALTER TABLE likes ADD session_id UUID DEFAULT NULL');
        $this->addSql('ALTER TABLE likes DROP article');
        $this->addSql('ALTER TABLE likes DROP session');
        $this->addSql('ALTER TABLE likes ALTER id TYPE UUID');
        $this->addSql('ALTER TABLE likes ALTER id DROP DEFAULT');
        $this->addSql('ALTER TABLE likes ALTER reaction_type SET NOT NULL');
        $this->addSql('COMMENT ON COLUMN likes.article_id IS \'(DC2Type:uuid)\'');
        $this->addSql('COMMENT ON COLUMN likes.session_id IS \'(DC2Type:uuid)\'');
        $this->addSql('COMMENT ON COLUMN likes.id IS \'(DC2Type:uuid)\'');
        $this->addSql('ALTER TABLE likes ADD CONSTRAINT FK_49CA4E7D7294869C FOREIGN KEY (article_id) REFERENCES articles (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE likes ADD CONSTRAINT FK_49CA4E7D613FECDF FOREIGN KEY (session_id) REFERENCES sessions (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('CREATE INDEX IDX_49CA4E7D7294869C ON likes (article_id)');
        $this->addSql('CREATE INDEX IDX_49CA4E7D613FECDF ON likes (session_id)');
        $this->addSql('ALTER TABLE notifications ALTER id TYPE UUID');
        $this->addSql('ALTER TABLE notifications ALTER id DROP DEFAULT');
        $this->addSql('ALTER TABLE notifications ALTER session_id TYPE UUID');
        $this->addSql('ALTER TABLE notifications ALTER session_id DROP DEFAULT');
        $this->addSql('ALTER TABLE notifications ALTER session_id DROP NOT NULL');
        $this->addSql('COMMENT ON COLUMN notifications.id IS \'(DC2Type:uuid)\'');
        $this->addSql('COMMENT ON COLUMN notifications.session_id IS \'(DC2Type:uuid)\'');
        $this->addSql('ALTER TABLE notifications ADD CONSTRAINT FK_6000B0D3613FECDF FOREIGN KEY (session_id) REFERENCES sessions (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE sessions ALTER id TYPE UUID');
        $this->addSql('ALTER TABLE sessions ALTER id DROP DEFAULT');
        $this->addSql('COMMENT ON COLUMN sessions.id IS \'(DC2Type:uuid)\'');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE SCHEMA public');
        $this->addSql('ALTER TABLE articles ALTER id TYPE UUID');
        $this->addSql('ALTER TABLE articles ALTER id DROP DEFAULT');
        $this->addSql('COMMENT ON COLUMN articles.id IS NULL');
        $this->addSql('ALTER TABLE likes DROP CONSTRAINT FK_49CA4E7D7294869C');
        $this->addSql('ALTER TABLE likes DROP CONSTRAINT FK_49CA4E7D613FECDF');
        $this->addSql('DROP INDEX IDX_49CA4E7D7294869C');
        $this->addSql('DROP INDEX IDX_49CA4E7D613FECDF');
        $this->addSql('ALTER TABLE likes ADD article INT DEFAULT NULL');
        $this->addSql('ALTER TABLE likes ADD session VARCHAR(255) NOT NULL');
        $this->addSql('ALTER TABLE likes DROP article_id');
        $this->addSql('ALTER TABLE likes DROP session_id');
        $this->addSql('ALTER TABLE likes ALTER id TYPE UUID');
        $this->addSql('ALTER TABLE likes ALTER id DROP DEFAULT');
        $this->addSql('ALTER TABLE likes ALTER reaction_type DROP NOT NULL');
        $this->addSql('COMMENT ON COLUMN likes.id IS NULL');
        $this->addSql('ALTER TABLE sessions ALTER id TYPE UUID');
        $this->addSql('ALTER TABLE sessions ALTER id DROP DEFAULT');
        $this->addSql('COMMENT ON COLUMN sessions.id IS NULL');
        $this->addSql('ALTER TABLE notifications DROP CONSTRAINT FK_6000B0D3613FECDF');
        $this->addSql('ALTER TABLE notifications ALTER id TYPE UUID');
        $this->addSql('ALTER TABLE notifications ALTER id DROP DEFAULT');
        $this->addSql('ALTER TABLE notifications ALTER session_id TYPE UUID');
        $this->addSql('ALTER TABLE notifications ALTER session_id DROP DEFAULT');
        $this->addSql('ALTER TABLE notifications ALTER session_id SET NOT NULL');
        $this->addSql('COMMENT ON COLUMN notifications.id IS NULL');
        $this->addSql('COMMENT ON COLUMN notifications.session_id IS NULL');
    }
}
