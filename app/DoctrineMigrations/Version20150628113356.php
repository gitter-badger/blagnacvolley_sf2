<?php

namespace Application\Migrations;

use Doctrine\DBAL\Migrations\AbstractMigration;
use Doctrine\DBAL\Schema\Schema;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
class Version20150628113356 extends AbstractMigration
{
    /**
     * @param Schema $schema
     */
    public function up(Schema $schema)
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() != 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE bv_user ADD is_looking_for_mix_team TINYINT(1) DEFAULT \'0\' NOT NULL, ADD is_looking_for_fem_team TINYINT(1) DEFAULT \'0\' NOT NULL, CHANGE is_looking_for_team is_looking_for_msc_team TINYINT(1) DEFAULT \'0\' NOT NULL');
    }

    /**
     * @param Schema $schema
     */
    public function down(Schema $schema)
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() != 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE bv_user ADD is_looking_for_team TINYINT(1) DEFAULT \'0\' NOT NULL, DROP is_looking_for_msc_team, DROP is_looking_for_mix_team, DROP is_looking_for_fem_team');
    }
}
