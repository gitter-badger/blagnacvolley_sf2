<?php

namespace Application\Migrations;

use Doctrine\DBAL\Migrations\AbstractMigration;
use Doctrine\DBAL\Schema\Schema;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
class Version20141008140858 extends AbstractMigration
{
    public function up(Schema $schema)
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() != 'mysql', 'Migration can only be executed safely on \'mysql\'.');
        
        $this->addSql('CREATE TABLE news__post_tag (post_id INT NOT NULL, tag_id INT NOT NULL, INDEX IDX_682B20514B89032C (post_id), INDEX IDX_682B2051BAD26311 (tag_id), PRIMARY KEY(post_id, tag_id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE classification__category (id INT AUTO_INCREMENT NOT NULL, parent_id INT DEFAULT NULL, media_id INT DEFAULT NULL, name VARCHAR(255) NOT NULL, enabled TINYINT(1) NOT NULL, slug VARCHAR(255) NOT NULL, description VARCHAR(255) DEFAULT NULL, position INT DEFAULT NULL, created_at DATETIME NOT NULL, updated_at DATETIME NOT NULL, INDEX IDX_43629B36727ACA70 (parent_id), INDEX IDX_43629B36EA9FDD75 (media_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE classification__collection (id INT AUTO_INCREMENT NOT NULL, media_id INT DEFAULT NULL, name VARCHAR(255) NOT NULL, enabled TINYINT(1) NOT NULL, slug VARCHAR(255) NOT NULL, description VARCHAR(255) DEFAULT NULL, created_at DATETIME NOT NULL, updated_at DATETIME NOT NULL, INDEX IDX_A406B56AEA9FDD75 (media_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE classification__tag (id INT AUTO_INCREMENT NOT NULL, name VARCHAR(255) NOT NULL, enabled TINYINT(1) NOT NULL, slug VARCHAR(255) NOT NULL, created_at DATETIME NOT NULL, updated_at DATETIME NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB');
        $this->addSql('ALTER TABLE news__post_tag ADD CONSTRAINT FK_682B20514B89032C FOREIGN KEY (post_id) REFERENCES news__post (id)');
        $this->addSql('ALTER TABLE news__post_tag ADD CONSTRAINT FK_682B2051BAD26311 FOREIGN KEY (tag_id) REFERENCES classification__tag (id)');
        $this->addSql('ALTER TABLE classification__category ADD CONSTRAINT FK_43629B36727ACA70 FOREIGN KEY (parent_id) REFERENCES classification__category (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE classification__category ADD CONSTRAINT FK_43629B36EA9FDD75 FOREIGN KEY (media_id) REFERENCES media__media (id) ON DELETE SET NULL');
        $this->addSql('ALTER TABLE classification__collection ADD CONSTRAINT FK_A406B56AEA9FDD75 FOREIGN KEY (media_id) REFERENCES media__media (id) ON DELETE SET NULL');
        $this->addSql('ALTER TABLE news__comment ADD post_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE news__comment ADD CONSTRAINT FK_A90210404B89032C FOREIGN KEY (post_id) REFERENCES news__post (id)');
        $this->addSql('CREATE INDEX IDX_A90210404B89032C ON news__comment (post_id)');
        $this->addSql('ALTER TABLE news__post ADD image_id INT DEFAULT NULL, ADD author_id INT DEFAULT NULL, ADD collection_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE news__post ADD CONSTRAINT FK_7D109BC83DA5256D FOREIGN KEY (image_id) REFERENCES media__media (id)');
        $this->addSql('ALTER TABLE news__post ADD CONSTRAINT FK_7D109BC8F675F31B FOREIGN KEY (author_id) REFERENCES fos_user_user (id)');
        $this->addSql('ALTER TABLE news__post ADD CONSTRAINT FK_7D109BC8514956FD FOREIGN KEY (collection_id) REFERENCES classification__collection (id)');
        $this->addSql('CREATE INDEX IDX_7D109BC83DA5256D ON news__post (image_id)');
        $this->addSql('CREATE INDEX IDX_7D109BC8F675F31B ON news__post (author_id)');
        $this->addSql('CREATE INDEX IDX_7D109BC8514956FD ON news__post (collection_id)');
    }

    public function down(Schema $schema)
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() != 'mysql', 'Migration can only be executed safely on \'mysql\'.');
        
        $this->addSql('ALTER TABLE classification__category DROP FOREIGN KEY FK_43629B36727ACA70');
        $this->addSql('ALTER TABLE news__post DROP FOREIGN KEY FK_7D109BC8514956FD');
        $this->addSql('ALTER TABLE news__post_tag DROP FOREIGN KEY FK_682B2051BAD26311');
        $this->addSql('DROP TABLE news__post_tag');
        $this->addSql('DROP TABLE classification__category');
        $this->addSql('DROP TABLE classification__collection');
        $this->addSql('DROP TABLE classification__tag');
        $this->addSql('ALTER TABLE news__comment DROP FOREIGN KEY FK_A90210404B89032C');
        $this->addSql('DROP INDEX IDX_A90210404B89032C ON news__comment');
        $this->addSql('ALTER TABLE news__comment DROP post_id');
        $this->addSql('DROP INDEX IDX_7D109BC83DA5256D ON news__post');
        $this->addSql('DROP INDEX IDX_7D109BC8F675F31B ON news__post');
        $this->addSql('DROP INDEX IDX_7D109BC8514956FD ON news__post');
        $this->addSql('ALTER TABLE news__post DROP image_id, DROP author_id, DROP collection_id');
    }
}
