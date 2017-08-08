<?php

namespace Site\BackendBundle\Migrations;

use Doctrine\DBAL\Migrations\AbstractMigration;
use Doctrine\DBAL\Schema\Schema;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
class Version20170724111120 extends AbstractMigration
{
    /**
     * @param Schema $schema
     */
    public function up(Schema $schema)
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('CREATE TABLE comment_table (id INT AUTO_INCREMENT NOT NULL, title_field VARCHAR(255) DEFAULT NULL, text_field LONGTEXT DEFAULT NULL, state_field VARCHAR(255) DEFAULT NULL, url_field LONGTEXT DEFAULT NULL, created_at DATETIME NOT NULL, updated_at DATETIME NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB');
        $this->addSql('DROP TABLE set_has_insertion_color');
        $this->addSql('ALTER TABLE callback_table ADD state_field VARCHAR(255) DEFAULT NULL');
    }

    /**
     * @param Schema $schema
     */
    public function down(Schema $schema)
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('CREATE TABLE set_has_insertion_color (insertion_color_id INT NOT NULL, set_id INT NOT NULL, INDEX IDX_61FBF4B5B7A363CE (insertion_color_id), INDEX IDX_61FBF4B510FB0D18 (set_id), PRIMARY KEY(insertion_color_id, set_id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB');
        $this->addSql('ALTER TABLE set_has_insertion_color ADD CONSTRAINT FK_61FBF4B510FB0D18 FOREIGN KEY (set_id) REFERENCES set_table (id)');
        $this->addSql('ALTER TABLE set_has_insertion_color ADD CONSTRAINT FK_61FBF4B5B7A363CE FOREIGN KEY (insertion_color_id) REFERENCES insertion_color_table (id)');
        $this->addSql('DROP TABLE comment_table');
        $this->addSql('ALTER TABLE callback_table DROP state_field');
    }
}
