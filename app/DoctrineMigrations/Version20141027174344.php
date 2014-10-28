<?php

namespace Application\Migrations;

use Doctrine\DBAL\Migrations\AbstractMigration;
use Doctrine\DBAL\Schema\Schema;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
class Version20141027174344 extends AbstractMigration
{
    public function up(Schema $schema)
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() != 'mysql', 'Migration can only be executed safely on \'mysql\'.');
        
        $this->addSql('ALTER TABLE events ADD team_id INT DEFAULT NULL, ADD type VARCHAR(255) NOT NULL');
        $this->addSql('ALTER TABLE events ADD CONSTRAINT FK_542B527C296CD8AE FOREIGN KEY (team_id) REFERENCES bv_team (id) ON DELETE SET NULL');
        $this->addSql('CREATE INDEX IDX_542B527C296CD8AE ON events (team_id)');
    }

    public function down(Schema $schema)
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() != 'mysql', 'Migration can only be executed safely on \'mysql\'.');
        
        $this->addSql('ALTER TABLE Events DROP FOREIGN KEY FK_542B527C296CD8AE');
        $this->addSql('DROP INDEX IDX_542B527C296CD8AE ON Events');
        $this->addSql('ALTER TABLE Events DROP team_id, DROP type');
    }
}
