<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20230606135643 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE category ADD administrator_id INT NOT NULL');
        $this->addSql('ALTER TABLE category ADD CONSTRAINT FK_64C19C14B09E92C FOREIGN KEY (administrator_id) REFERENCES `admin` (id)');
        $this->addSql('CREATE INDEX IDX_64C19C14B09E92C ON category (administrator_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE category DROP FOREIGN KEY FK_64C19C14B09E92C');
        $this->addSql('DROP INDEX IDX_64C19C14B09E92C ON category');
        $this->addSql('ALTER TABLE category DROP administrator_id');
    }
}
