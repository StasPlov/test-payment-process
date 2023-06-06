<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20230606041659 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE coupon (code VARCHAR(255) NOT NULL, type_id INT NOT NULL, discount INT NOT NULL, INDEX IDX_64BF3F02C54C8C93 (type_id), PRIMARY KEY(code)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE coupon_type (id INT AUTO_INCREMENT NOT NULL, title VARCHAR(1000) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE coupon ADD CONSTRAINT FK_64BF3F02C54C8C93 FOREIGN KEY (type_id) REFERENCES coupon_type (id)');
        $this->addSql('ALTER TABLE module DROP FOREIGN KEY FK_C242628727ACA70');
        $this->addSql('DROP TABLE module');
        $this->addSql('ALTER TABLE product CHANGE created_at created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP, CHANGE update_at update_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE module (id INT AUTO_INCREMENT NOT NULL, parent_id INT DEFAULT NULL, created_at DATETIME DEFAULT CURRENT_TIMESTAMP NOT NULL, update_at DATETIME DEFAULT CURRENT_TIMESTAMP NOT NULL, title VARCHAR(255) CHARACTER SET utf8mb4 NOT NULL COLLATE `utf8mb4_unicode_ci`, description VARCHAR(255) CHARACTER SET utf8mb4 DEFAULT NULL COLLATE `utf8mb4_unicode_ci`, breadcrumb VARCHAR(255) CHARACTER SET utf8mb4 NOT NULL COLLATE `utf8mb4_unicode_ci`, is_delete TINYINT(1) NOT NULL, is_hide TINYINT(1) NOT NULL, can_delete TINYINT(1) NOT NULL, is_main TINYINT(1) NOT NULL, UNIQUE INDEX UNIQ_C242628F0132905 (breadcrumb), INDEX IDX_C242628727ACA70 (parent_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB COMMENT = \'\' ');
        $this->addSql('ALTER TABLE module ADD CONSTRAINT FK_C242628727ACA70 FOREIGN KEY (parent_id) REFERENCES module (id)');
        $this->addSql('ALTER TABLE coupon DROP FOREIGN KEY FK_64BF3F02C54C8C93');
        $this->addSql('DROP TABLE coupon');
        $this->addSql('DROP TABLE coupon_type');
        $this->addSql('ALTER TABLE product CHANGE created_at created_at DATETIME DEFAULT CURRENT_TIMESTAMP NOT NULL, CHANGE update_at update_at DATETIME DEFAULT CURRENT_TIMESTAMP NOT NULL');
    }
}
