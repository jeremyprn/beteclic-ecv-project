<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20230601155933 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE event ADD selection_event_id_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE event ADD CONSTRAINT FK_3BAE0AA7B4222900 FOREIGN KEY (selection_event_id_id) REFERENCES selection_event (id)');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_3BAE0AA7B4222900 ON event (selection_event_id_id)');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_8D93D649E7927C74 ON user (email)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE event DROP FOREIGN KEY FK_3BAE0AA7B4222900');
        $this->addSql('DROP INDEX UNIQ_3BAE0AA7B4222900 ON event');
        $this->addSql('ALTER TABLE event DROP selection_event_id_id');
        $this->addSql('DROP INDEX UNIQ_8D93D649E7927C74 ON user');
    }
}
