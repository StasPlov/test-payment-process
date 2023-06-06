<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20230606034205 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE country (code VARCHAR(2) NOT NULL, name VARCHAR(1000) NOT NULL, PRIMARY KEY(code)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE country_tax (country_id VARCHAR(2) NOT NULL, tax DOUBLE PRECISION NOT NULL, PRIMARY KEY(country_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE module (id INT AUTO_INCREMENT NOT NULL, parent_id INT DEFAULT NULL, created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP, update_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP, title VARCHAR(255) NOT NULL, description VARCHAR(255) DEFAULT NULL, breadcrumb VARCHAR(255) NOT NULL, is_delete TINYINT(1) NOT NULL, is_hide TINYINT(1) NOT NULL, can_delete TINYINT(1) NOT NULL, is_main TINYINT(1) NOT NULL, UNIQUE INDEX UNIQ_C242628F0132905 (breadcrumb), INDEX IDX_C242628727ACA70 (parent_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE product (id INT AUTO_INCREMENT NOT NULL, created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP, update_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP, name VARCHAR(255) NOT NULL, description VARCHAR(2000) DEFAULT NULL, price DOUBLE PRECISION NOT NULL, is_delete TINYINT(1) NOT NULL, is_hide TINYINT(1) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE country_tax ADD CONSTRAINT FK_B5A98CE7F92F3E70 FOREIGN KEY (country_id) REFERENCES country (code)');
        $this->addSql('ALTER TABLE module ADD CONSTRAINT FK_C242628727ACA70 FOREIGN KEY (parent_id) REFERENCES module (id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE country_tax DROP FOREIGN KEY FK_B5A98CE7F92F3E70');
        $this->addSql('ALTER TABLE module DROP FOREIGN KEY FK_C242628727ACA70');
        $this->addSql('DROP TABLE country');
        $this->addSql('DROP TABLE country_tax');
        $this->addSql('DROP TABLE module');
        $this->addSql('DROP TABLE product');
    }
}
