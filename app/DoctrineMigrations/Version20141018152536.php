<?php

namespace Application\Migrations;

use Doctrine\DBAL\Migrations\AbstractMigration;
use Doctrine\DBAL\Schema\Schema;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
class Version20141018152536 extends AbstractMigration
{
    public function up(Schema $schema)
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() != 'mysql', 'Migration can only be executed safely on \'mysql\'.');
        
        $this->addSql('ALTER TABLE bv_user CHANGE shirt_size shirt_size VARCHAR(5) DEFAULT NULL, CHANGE is_required_bill is_required_bill TINYINT(1) DEFAULT \'0\' NOT NULL, CHANGE is_looking_for_team is_looking_for_team TINYINT(1) DEFAULT \'0\' NOT NULL, CHANGE level level VARCHAR(255) DEFAULT NULL, CHANGE status status VARCHAR(255) DEFAULT \'ACTIF_NON_LICENCIE\' NOT NULL, CHANGE licence_number licence_number VARCHAR(255) DEFAULT NULL, CHANGE fee_amount fee_amount DOUBLE PRECISION DEFAULT NULL, CHANGE date_payment date_payment DATETIME DEFAULT NULL, CHANGE date_shirt_delivered date_shirt_delivered DATETIME DEFAULT NULL');
    }

    public function down(Schema $schema)
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() != 'mysql', 'Migration can only be executed safely on \'mysql\'.');
        
        $this->addSql('ALTER TABLE bv_user CHANGE shirt_size shirt_size VARCHAR(5) NOT NULL, CHANGE is_required_bill is_required_bill TINYINT(1) NOT NULL, CHANGE is_looking_for_team is_looking_for_team TINYINT(1) NOT NULL, CHANGE level level VARCHAR(255) NOT NULL, CHANGE status status VARCHAR(255) NOT NULL, CHANGE licence_number licence_number VARCHAR(255) NOT NULL, CHANGE fee_amount fee_amount DOUBLE PRECISION NOT NULL, CHANGE date_payment date_payment DATETIME NOT NULL, CHANGE date_shirt_delivered date_shirt_delivered DATETIME NOT NULL');
    }
}
