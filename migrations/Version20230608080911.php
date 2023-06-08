<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20230608080911 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE `admin` (id INT AUTO_INCREMENT NOT NULL, email VARCHAR(180) NOT NULL, roles JSON NOT NULL, password VARCHAR(255) NOT NULL, UNIQUE INDEX UNIQ_880E0D76E7927C74 (email), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE category (id INT AUTO_INCREMENT NOT NULL, administrator_id INT NOT NULL, name VARCHAR(100) NOT NULL, slug VARCHAR(255) NOT NULL, INDEX IDX_64C19C14B09E92C (administrator_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE contact (id INT AUTO_INCREMENT NOT NULL, administrator_id INT NOT NULL, name VARCHAR(100) NOT NULL, email VARCHAR(50) NOT NULL, subject VARCHAR(100) NOT NULL, message LONGTEXT NOT NULL, created_at DATETIME NOT NULL, INDEX IDX_4C62E6384B09E92C (administrator_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE news (id INT AUTO_INCREMENT NOT NULL, administrator_id INT NOT NULL, title VARCHAR(255) DEFAULT NULL, description LONGTEXT DEFAULT NULL, media VARCHAR(255) DEFAULT NULL, created_at DATETIME NOT NULL, slug VARCHAR(255) NOT NULL, INDEX IDX_1DD399504B09E92C (administrator_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE work (id INT AUTO_INCREMENT NOT NULL, administrator_id INT NOT NULL, category_id INT NOT NULL, technique VARCHAR(100) NOT NULL, format VARCHAR(100) NOT NULL, title VARCHAR(255) NOT NULL, image VARCHAR(255) NOT NULL, image_detail LONGTEXT DEFAULT NULL COMMENT \'(DC2Type:array)\', description LONGTEXT DEFAULT NULL, date SMALLINT DEFAULT NULL, localisation VARCHAR(255) DEFAULT NULL, price INT DEFAULT NULL, quantity SMALLINT NOT NULL, saleable TINYINT(1) NOT NULL, slug VARCHAR(255) NOT NULL, INDEX IDX_534E68804B09E92C (administrator_id), INDEX IDX_534E688012469DE2 (category_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE category ADD CONSTRAINT FK_64C19C14B09E92C FOREIGN KEY (administrator_id) REFERENCES `admin` (id)');
        $this->addSql('ALTER TABLE contact ADD CONSTRAINT FK_4C62E6384B09E92C FOREIGN KEY (administrator_id) REFERENCES `admin` (id)');
        $this->addSql('ALTER TABLE news ADD CONSTRAINT FK_1DD399504B09E92C FOREIGN KEY (administrator_id) REFERENCES `admin` (id)');
        $this->addSql('ALTER TABLE work ADD CONSTRAINT FK_534E68804B09E92C FOREIGN KEY (administrator_id) REFERENCES `admin` (id)');
        $this->addSql('ALTER TABLE work ADD CONSTRAINT FK_534E688012469DE2 FOREIGN KEY (category_id) REFERENCES category (id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE category DROP FOREIGN KEY FK_64C19C14B09E92C');
        $this->addSql('ALTER TABLE contact DROP FOREIGN KEY FK_4C62E6384B09E92C');
        $this->addSql('ALTER TABLE news DROP FOREIGN KEY FK_1DD399504B09E92C');
        $this->addSql('ALTER TABLE work DROP FOREIGN KEY FK_534E68804B09E92C');
        $this->addSql('ALTER TABLE work DROP FOREIGN KEY FK_534E688012469DE2');
        $this->addSql('DROP TABLE `admin`');
        $this->addSql('DROP TABLE category');
        $this->addSql('DROP TABLE contact');
        $this->addSql('DROP TABLE news');
        $this->addSql('DROP TABLE work');
    }
}
