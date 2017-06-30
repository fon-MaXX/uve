<?php

namespace Site\BackendBundle\Migrations;

use Doctrine\DBAL\Migrations\AbstractMigration;
use Doctrine\DBAL\Schema\Schema;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
class Version20170619122153 extends AbstractMigration
{
    /**
     * @param Schema $schema
     */
    public function up(Schema $schema)
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('CREATE TABLE nova_poshta_data_table (id INT AUTO_INCREMENT NOT NULL, order_id INT DEFAULT NULL, payer LONGTEXT DEFAULT NULL, region_href LONGTEXT DEFAULT NULL, region_name LONGTEXT DEFAULT NULL, city_href LONGTEXT DEFAULT NULL, city_name LONGTEXT DEFAULT NULL, warehouse_href LONGTEXT DEFAULT NULL, warehouse_name LONGTEXT DEFAULT NULL, invoice_ref LONGTEXT DEFAULT NULL, new_post_state LONGTEXT DEFAULT NULL, new_post_state_id LONGTEXT DEFAULT NULL, c_o_d_state LONGTEXT DEFAULT NULL, c_o_d_transaction_date LONGTEXT DEFAULT NULL, invoice_id LONGTEXT DEFAULT NULL, UNIQUE INDEX UNIQ_99F2FEC68D9F6D38 (order_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE nova_poshta_region_table (id INT AUTO_INCREMENT NOT NULL, description LONGTEXT NOT NULL, ref LONGTEXT NOT NULL, areas_center LONGTEXT NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB');
        $this->addSql('ALTER TABLE nova_poshta_data_table ADD CONSTRAINT FK_99F2FEC68D9F6D38 FOREIGN KEY (order_id) REFERENCES order_table (id)');
        $this->addSql('DROP TABLE set_has_insertion_color');
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
        $this->addSql('DROP TABLE nova_poshta_data_table');
        $this->addSql('DROP TABLE nova_poshta_region_table');
    }
}
