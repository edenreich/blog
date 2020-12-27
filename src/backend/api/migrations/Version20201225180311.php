<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20201225180311 extends AbstractMigration
{
    public function getDescription(): string
    {
        return 'Make created_at not nullable.';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE notifications ALTER created_at SET NOT NULL');
        $this->addSql('ALTER TABLE reactions ALTER created_at SET NOT NULL');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE SCHEMA public');
        $this->addSql('ALTER TABLE notifications ALTER created_at DROP NOT NULL');
        $this->addSql('ALTER TABLE reactions ALTER created_at DROP NOT NULL');
    }
}
