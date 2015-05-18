<?php

namespace Application\Migrations;

use Doctrine\DBAL\Migrations\AbstractMigration;
use Doctrine\DBAL\Schema\Schema;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
class Version20150518153430 extends AbstractMigration
{
    /**
     * @param Schema $schema
     */
    public function up(Schema $schema)
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() != 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE bv_news DROP FOREIGN KEY FK_DAEA7238AE48C21E');
        $this->addSql('DROP INDEX idx_daea7238ae48c21e ON bv_news');
        $this->addSql('CREATE INDEX IDX_DAEA7238BDAFD8C8 ON bv_news (author)');
        $this->addSql('ALTER TABLE bv_news ADD CONSTRAINT FK_DAEA7238AE48C21E FOREIGN KEY (author) REFERENCES bv_user (id) ON DELETE SET NULL');
        $this->addSql('ALTER TABLE bv_user ADD desk_role VARCHAR(255) DEFAULT NULL, ADD joined_desk_at DATETIME NOT NULL');
    }

    /**
     * @param Schema $schema
     */
    public function down(Schema $schema)
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() != 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE bv_news DROP FOREIGN KEY FK_DAEA7238BDAFD8C8');
        $this->addSql('DROP INDEX idx_daea7238bdafd8c8 ON bv_news');
        $this->addSql('CREATE INDEX IDX_DAEA7238AE48C21E ON bv_news (author)');
        $this->addSql('ALTER TABLE bv_news ADD CONSTRAINT FK_DAEA7238BDAFD8C8 FOREIGN KEY (author) REFERENCES bv_user (id) ON DELETE SET NULL');
        $this->addSql('ALTER TABLE bv_user DROP desk_role, DROP joined_desk_at');
    }
}
