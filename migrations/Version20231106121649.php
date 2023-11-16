<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20231106121649 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE order_details_work DROP FOREIGN KEY FK_73582E58C0FA77');
        $this->addSql('ALTER TABLE order_details_work DROP FOREIGN KEY FK_73582E5BB3453DB');
        $this->addSql('DROP TABLE order_details_work');
        $this->addSql('ALTER TABLE order_details ADD work_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE order_details ADD CONSTRAINT FK_845CA2C1BB3453DB FOREIGN KEY (work_id) REFERENCES work (id)');
        $this->addSql('CREATE INDEX IDX_845CA2C1BB3453DB ON order_details (work_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE order_details_work (order_details_id INT NOT NULL, work_id INT NOT NULL, INDEX IDX_73582E58C0FA77 (order_details_id), INDEX IDX_73582E5BB3453DB (work_id), PRIMARY KEY(order_details_id, work_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB COMMENT = \'\' ');
        $this->addSql('ALTER TABLE order_details_work ADD CONSTRAINT FK_73582E58C0FA77 FOREIGN KEY (order_details_id) REFERENCES order_details (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE order_details_work ADD CONSTRAINT FK_73582E5BB3453DB FOREIGN KEY (work_id) REFERENCES work (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE order_details DROP FOREIGN KEY FK_845CA2C1BB3453DB');
        $this->addSql('DROP INDEX IDX_845CA2C1BB3453DB ON order_details');
        $this->addSql('ALTER TABLE order_details DROP work_id');
    }
}
