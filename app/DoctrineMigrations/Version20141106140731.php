<?php

namespace Application\Migrations;

use Doctrine\DBAL\Migrations\AbstractMigration;
use Doctrine\DBAL\Schema\Schema;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
class Version20141106140731 extends AbstractMigration
{
    public function up(Schema $schema)
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() != 'mysql', 'Migration can only be executed safely on \'mysql\'.');
        
        $this->addSql('ALTER TABLE bv_user ADD date_certif DATETIME DEFAULT NULL, ADD date_attestation DATETIME DEFAULT NULL');
        $this->addSql('ALTER TABLE system_log ADD user INT DEFAULT NULL');
        $this->addSql('ALTER TABLE system_log ADD CONSTRAINT FK_4287C21E8D93D649 FOREIGN KEY (user) REFERENCES bv_user (id) ON DELETE SET NULL');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_4287C21E8D93D649 ON system_log (user)');
    }

    public function down(Schema $schema)
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() != 'mysql', 'Migration can only be executed safely on \'mysql\'.');
        
        $this->addSql('ALTER TABLE bv_user DROP date_certif, DROP date_attestation');
        $this->addSql('ALTER TABLE system_log DROP FOREIGN KEY FK_4287C21E8D93D649');
        $this->addSql('DROP INDEX UNIQ_4287C21E8D93D649 ON system_log');
        $this->addSql('ALTER TABLE system_log DROP user');
    }
}
