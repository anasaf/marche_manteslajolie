<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20250919211732 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE commercant ADD adresse VARCHAR(255) DEFAULT NULL');
        $this->addSql('ALTER TABLE commercant ADD telephone VARCHAR(50) DEFAULT NULL');
        $this->addSql('ALTER TABLE commercant ADD email VARCHAR(100) DEFAULT NULL');
        $this->addSql('ALTER TABLE commercant ADD logo_name VARCHAR(255) DEFAULT NULL');
        $this->addSql('ALTER TABLE product DROP CONSTRAINT fk_d34a04ad12469de2');
        $this->addSql('ALTER TABLE product DROP CONSTRAINT fk_d34a04ad896dbbde');
        $this->addSql('ALTER TABLE product DROP CONSTRAINT fk_d34a04adb03a8386');
        $this->addSql('DROP INDEX idx_d34a04ad12469de2');
        $this->addSql('DROP INDEX idx_d34a04ad896dbbde');
        $this->addSql('DROP INDEX idx_d34a04adb03a8386');
        $this->addSql('ALTER TABLE product ADD categorie_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE product ADD nom VARCHAR(255) NOT NULL');
        $this->addSql('ALTER TABLE product DROP category_id');
        $this->addSql('ALTER TABLE product DROP created_by_id');
        $this->addSql('ALTER TABLE product DROP updated_by_id');
        $this->addSql('ALTER TABLE product DROP name');
        $this->addSql('ALTER TABLE product DROP created_at');
        $this->addSql('ALTER TABLE product ALTER commercant_id DROP NOT NULL');
        $this->addSql('ALTER TABLE product RENAME COLUMN price TO prix');
        $this->addSql('ALTER TABLE product RENAME COLUMN image TO image_name');
        $this->addSql('ALTER TABLE product ADD CONSTRAINT FK_D34A04ADBCF5E72D FOREIGN KEY (categorie_id) REFERENCES category (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('CREATE INDEX IDX_D34A04ADBCF5E72D ON product (categorie_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE SCHEMA public');
        $this->addSql('ALTER TABLE product DROP CONSTRAINT FK_D34A04ADBCF5E72D');
        $this->addSql('DROP INDEX IDX_D34A04ADBCF5E72D');
        $this->addSql('ALTER TABLE product ADD created_by_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE product ADD updated_by_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE product ADD name VARCHAR(200) NOT NULL');
        $this->addSql('ALTER TABLE product ADD created_at TIMESTAMP(0) WITHOUT TIME ZONE NOT NULL');
        $this->addSql('ALTER TABLE product DROP nom');
        $this->addSql('ALTER TABLE product ALTER commercant_id SET NOT NULL');
        $this->addSql('ALTER TABLE product RENAME COLUMN categorie_id TO category_id');
        $this->addSql('ALTER TABLE product RENAME COLUMN prix TO price');
        $this->addSql('ALTER TABLE product RENAME COLUMN image_name TO image');
        $this->addSql('ALTER TABLE product ADD CONSTRAINT fk_d34a04ad12469de2 FOREIGN KEY (category_id) REFERENCES category (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE product ADD CONSTRAINT fk_d34a04ad896dbbde FOREIGN KEY (updated_by_id) REFERENCES app_user (id) ON DELETE SET NULL NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE product ADD CONSTRAINT fk_d34a04adb03a8386 FOREIGN KEY (created_by_id) REFERENCES app_user (id) ON DELETE SET NULL NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('CREATE INDEX idx_d34a04ad12469de2 ON product (category_id)');
        $this->addSql('CREATE INDEX idx_d34a04ad896dbbde ON product (updated_by_id)');
        $this->addSql('CREATE INDEX idx_d34a04adb03a8386 ON product (created_by_id)');
        $this->addSql('ALTER TABLE commercant DROP adresse');
        $this->addSql('ALTER TABLE commercant DROP telephone');
        $this->addSql('ALTER TABLE commercant DROP email');
        $this->addSql('ALTER TABLE commercant DROP logo_name');
    }
}
