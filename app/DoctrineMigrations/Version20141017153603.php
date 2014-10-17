<?php

namespace Application\Migrations;

use Doctrine\DBAL\Migrations\AbstractMigration;
use Doctrine\DBAL\Schema\Schema;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
class Version20141017153603 extends AbstractMigration
{
    public function up(Schema $schema)
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() != 'mysql', 'Migration can only be executed safely on \'mysql\'.');
        
        $this->addSql('ALTER TABLE bv_user DROP INDEX UNIQ_4AAA3D21B0510F93, ADD INDEX IDX_4AAA3D21B0510F93 (msc_team_id)');
        $this->addSql('ALTER TABLE bv_user DROP INDEX UNIQ_4AAA3D217102A2EA, ADD INDEX IDX_4AAA3D217102A2EA (fem_team_id)');
        $this->addSql('ALTER TABLE bv_user DROP INDEX UNIQ_4AAA3D21AE48C21E, ADD INDEX IDX_4AAA3D21AE48C21E (mix_team_id)');
    }

    public function down(Schema $schema)
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() != 'mysql', 'Migration can only be executed safely on \'mysql\'.');
        
        $this->addSql('ALTER TABLE bv_user DROP INDEX IDX_4AAA3D21B0510F93, ADD UNIQUE INDEX UNIQ_4AAA3D21B0510F93 (msc_team_id)');
        $this->addSql('ALTER TABLE bv_user DROP INDEX IDX_4AAA3D217102A2EA, ADD UNIQUE INDEX UNIQ_4AAA3D217102A2EA (fem_team_id)');
        $this->addSql('ALTER TABLE bv_user DROP INDEX IDX_4AAA3D21AE48C21E, ADD UNIQUE INDEX UNIQ_4AAA3D21AE48C21E (mix_team_id)');
    }
}
