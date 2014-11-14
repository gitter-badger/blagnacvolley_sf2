<?php

namespace Application\Migrations;

use Doctrine\DBAL\Migrations\AbstractMigration;
use Doctrine\DBAL\Schema\Schema;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
class Version20141113145855 extends AbstractMigration
{
    public function up(Schema $schema)
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() != 'mysql', 'Migration can only be executed safely on \'mysql\'.');
        
        $this->addSql('ALTER TABLE bv_news ADD events_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE bv_news ADD CONSTRAINT FK_DAEA72389D6A1065 FOREIGN KEY (events_id) REFERENCES events (id) ON DELETE SET NULL');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_DAEA72389D6A1065 ON bv_news (events_id)');
    }

    public function down(Schema $schema)
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() != 'mysql', 'Migration can only be executed safely on \'mysql\'.');
        
        $this->addSql('ALTER TABLE bv_news DROP FOREIGN KEY FK_DAEA72389D6A1065');
        $this->addSql('DROP INDEX UNIQ_DAEA72389D6A1065 ON bv_news');
        $this->addSql('ALTER TABLE bv_news DROP events_id');
    }
}
