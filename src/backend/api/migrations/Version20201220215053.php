<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20201220215053 extends AbstractMigration
{
    public function getDescription() : string
    {
        return 'Make reaction unique identified by combination of session and article. One reaction per user per article.';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('DROP INDEX reactions_id_unique');
        $this->addSql('CREATE UNIQUE INDEX reactions_articles_sessions_id_unique ON reactions (id, article_id, session_id)');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE SCHEMA public');
        $this->addSql('DROP INDEX reactions_articles_sessions_id_unique');
        $this->addSql('CREATE UNIQUE INDEX reactions_id_unique ON reactions (id)');
    }
}
