<?php

namespace Application\Migrations;

use Doctrine\DBAL\Migrations\AbstractMigration;
use Doctrine\DBAL\Schema\Schema;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
class Version20150511110238 extends AbstractMigration
{
    /**
     * @param Schema $schema
     */
    public function up(Schema $schema)
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() != 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE bv_news ADD author INT DEFAULT NULL');
        $this->addSql('ALTER TABLE bv_news ADD CONSTRAINT FK_DAEA7238AE48C21E FOREIGN KEY (author) REFERENCES bv_user (id) ON DELETE SET NULL');
        $this->addSql('CREATE INDEX IDX_DAEA7238AE48C21E ON bv_news (author)');
    }

    /**
     * @param Schema $schema
     */
    public function down(Schema $schema)
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() != 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE bv_news DROP FOREIGN KEY FK_DAEA7238AE48C21E');
        $this->addSql('DROP INDEX IDX_DAEA7238AE48C21E ON bv_news');
        $this->addSql('ALTER TABLE bv_news DROP author');
    }
}
