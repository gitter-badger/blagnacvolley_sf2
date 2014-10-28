<?php

namespace Application\Migrations;

use Doctrine\DBAL\Migrations\AbstractMigration;
use Doctrine\DBAL\Schema\Schema;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
class Version20141027171820 extends AbstractMigration
{
    public function up(Schema $schema)
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() != 'mysql', 'Migration can only be executed safely on \'mysql\'.');
        
        $this->addSql('CREATE TABLE Events (id INT AUTO_INCREMENT NOT NULL, start_date DATETIME NOT NULL, end_date DATETIME NOT NULL, text VARCHAR(255) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE bv_group (id INT AUTO_INCREMENT NOT NULL, name VARCHAR(255) NOT NULL, roles LONGTEXT NOT NULL COMMENT \'(DC2Type:array)\', UNIQUE INDEX UNIQ_2E6E94445E237E06 (name), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE bv_news (id INT AUTO_INCREMENT NOT NULL, title VARCHAR(255) NOT NULL, content LONGTEXT NOT NULL, raw_content LONGTEXT NOT NULL, content_formatter VARCHAR(32) NOT NULL, enabled TINYINT(1) NOT NULL, slug TINYTEXT NOT NULL, created_at DATETIME NOT NULL, updated_at DATETIME NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE bv_team (id INT AUTO_INCREMENT NOT NULL, captain_id INT DEFAULT NULL, sub_captain_id INT DEFAULT NULL, level VARCHAR(255) NOT NULL, name VARCHAR(255) NOT NULL, type VARCHAR(255) NOT NULL, slot VARCHAR(255) NOT NULL, UNIQUE INDEX UNIQ_3D94D773346729B (captain_id), UNIQUE INDEX UNIQ_3D94D771F8DE156 (sub_captain_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE bv_user (id INT AUTO_INCREMENT NOT NULL, msc_team_id INT DEFAULT NULL, fem_team_id INT DEFAULT NULL, mix_team_id INT DEFAULT NULL, username VARCHAR(255) NOT NULL, username_canonical VARCHAR(255) NOT NULL, email VARCHAR(255) NOT NULL, email_canonical VARCHAR(255) NOT NULL, enabled TINYINT(1) NOT NULL, salt VARCHAR(255) NOT NULL, password VARCHAR(255) NOT NULL, last_login DATETIME DEFAULT NULL, locked TINYINT(1) NOT NULL, expired TINYINT(1) NOT NULL, expires_at DATETIME DEFAULT NULL, confirmation_token VARCHAR(255) DEFAULT NULL, password_requested_at DATETIME DEFAULT NULL, roles LONGTEXT NOT NULL COMMENT \'(DC2Type:array)\', credentials_expired TINYINT(1) NOT NULL, credentials_expire_at DATETIME DEFAULT NULL, gender VARCHAR(255) NOT NULL, firstname VARCHAR(255) NOT NULL, lastname VARCHAR(255) NOT NULL, dob DATETIME NOT NULL, address VARCHAR(512) NOT NULL, picture VARCHAR(255) NOT NULL, shirt_size VARCHAR(5) DEFAULT NULL, is_required_bill TINYINT(1) DEFAULT \'0\' NOT NULL, is_looking_for_team TINYINT(1) DEFAULT \'0\' NOT NULL, level VARCHAR(255) DEFAULT NULL, geo_lat VARCHAR(255) NOT NULL, geo_lng DOUBLE PRECISION NOT NULL, status VARCHAR(255) DEFAULT \'ACTIVE_NOT_LICENSED\' NOT NULL, license_number VARCHAR(255) DEFAULT NULL, fee_amount DOUBLE PRECISION DEFAULT NULL, date_payment DATETIME DEFAULT NULL, date_shirt_delivered DATETIME DEFAULT NULL, phone VARCHAR(255) DEFAULT NULL, UNIQUE INDEX UNIQ_4AAA3D2192FC23A8 (username_canonical), UNIQUE INDEX UNIQ_4AAA3D21A0D96FBF (email_canonical), INDEX IDX_4AAA3D21B0510F93 (msc_team_id), INDEX IDX_4AAA3D217102A2EA (fem_team_id), INDEX IDX_4AAA3D21AE48C21E (mix_team_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB');
        $this->addSql('ALTER TABLE bv_team ADD CONSTRAINT FK_3D94D773346729B FOREIGN KEY (captain_id) REFERENCES bv_user (id) ON DELETE SET NULL');
        $this->addSql('ALTER TABLE bv_team ADD CONSTRAINT FK_3D94D771F8DE156 FOREIGN KEY (sub_captain_id) REFERENCES bv_user (id) ON DELETE SET NULL');
        $this->addSql('ALTER TABLE bv_user ADD CONSTRAINT FK_4AAA3D21B0510F93 FOREIGN KEY (msc_team_id) REFERENCES bv_team (id) ON DELETE SET NULL');
        $this->addSql('ALTER TABLE bv_user ADD CONSTRAINT FK_4AAA3D217102A2EA FOREIGN KEY (fem_team_id) REFERENCES bv_team (id) ON DELETE SET NULL');
        $this->addSql('ALTER TABLE bv_user ADD CONSTRAINT FK_4AAA3D21AE48C21E FOREIGN KEY (mix_team_id) REFERENCES bv_team (id) ON DELETE SET NULL');
    }

    public function down(Schema $schema)
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() != 'mysql', 'Migration can only be executed safely on \'mysql\'.');
        
        $this->addSql('ALTER TABLE bv_user DROP FOREIGN KEY FK_4AAA3D21B0510F93');
        $this->addSql('ALTER TABLE bv_user DROP FOREIGN KEY FK_4AAA3D217102A2EA');
        $this->addSql('ALTER TABLE bv_user DROP FOREIGN KEY FK_4AAA3D21AE48C21E');
        $this->addSql('ALTER TABLE bv_team DROP FOREIGN KEY FK_3D94D773346729B');
        $this->addSql('ALTER TABLE bv_team DROP FOREIGN KEY FK_3D94D771F8DE156');
        $this->addSql('DROP TABLE Events');
        $this->addSql('DROP TABLE bv_group');
        $this->addSql('DROP TABLE bv_news');
        $this->addSql('DROP TABLE bv_team');
        $this->addSql('DROP TABLE bv_user');
    }
}
