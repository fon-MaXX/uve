<?php

namespace Site\BackendBundle\Migrations;

use Doctrine\DBAL\Migrations\AbstractMigration;
use Doctrine\DBAL\Schema\Schema;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
class Version20170511143754 extends AbstractMigration
{
    /**
     * @param Schema $schema
     */
    public function up(Schema $schema)
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('CREATE TABLE set_has_share_tag (share_tag_id INT NOT NULL, set_id INT NOT NULL, INDEX IDX_17C112C164E09338 (share_tag_id), INDEX IDX_17C112C110FB0D18 (set_id), PRIMARY KEY(share_tag_id, set_id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB');
        $this->addSql('ALTER TABLE set_has_share_tag ADD CONSTRAINT FK_17C112C164E09338 FOREIGN KEY (share_tag_id) REFERENCES share_tag_table (id)');
        $this->addSql('ALTER TABLE set_has_share_tag ADD CONSTRAINT FK_17C112C110FB0D18 FOREIGN KEY (set_id) REFERENCES set_table (id)');
    }

    /**
     * @param Schema $schema
     */
    public function down(Schema $schema)
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('DROP TABLE set_has_share_tag');
    }
}
