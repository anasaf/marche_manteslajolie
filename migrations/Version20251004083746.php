<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

final class Version20251004083746 extends AbstractMigration
{
    public function getDescription(): string
    {
        return 'Insert default categorys into category table with id and created_at';
    }

    public function up(Schema $schema): void
    {
        $this->addSql("INSERT INTO category (created_by_id, id, name, created_at) VALUES (1, 4, 'Linge de maison', NOW())");
        $this->addSql("INSERT INTO category (created_by_id, id, name, created_at) VALUES (1, 5, 'Maison & Décoration', NOW())");
        $this->addSql("INSERT INTO category (created_by_id, id, name, created_at) VALUES (1, 6, 'Vaisselle & Arts de la table', NOW())");
        $this->addSql("INSERT INTO category (created_by_id, id, name, created_at) VALUES (1, 7, 'Cuisine', NOW())");
        $this->addSql("INSERT INTO category (created_by_id, id, name, created_at) VALUES (1, 8, 'Électroménager', NOW())");
        $this->addSql("INSERT INTO category (created_by_id, id, name, created_at) VALUES (1, 9, 'Électronique / High-Tech', NOW())");
        $this->addSql("INSERT INTO category (created_by_id, id, name, created_at) VALUES (1, 10, 'Informatique & Logiciels', NOW())");
        $this->addSql("INSERT INTO category (created_by_id, id, name, created_at) VALUES (1, 11, 'Téléphonie & Accessoires', NOW())");
        $this->addSql("INSERT INTO category (created_by_id, id, name, created_at) VALUES (1, 12, 'Mode / Vêtements', NOW())");
        $this->addSql("INSERT INTO category (created_by_id, id, name, created_at) VALUES (1, 13, 'Chaussures', NOW())");
        $this->addSql("INSERT INTO category (created_by_id, id, name, created_at) VALUES (1, 14, 'Accessoires de mode', NOW())");
        $this->addSql("INSERT INTO category (created_by_id, id, name, created_at) VALUES (1, 15, 'Bijoux', NOW())");
        $this->addSql("INSERT INTO category (created_by_id, id, name, created_at) VALUES (1, 16, 'Jouets & Jeux', NOW())");
        $this->addSql("INSERT INTO category (created_by_id, id, name, created_at) VALUES (1, 17, 'Sports & Loisirs', NOW())");
        $this->addSql("INSERT INTO category (created_by_id, id, name, created_at) VALUES (1, 18, 'Santé & Bien-être', NOW())");
        $this->addSql("INSERT INTO category (created_by_id, id, name, created_at) VALUES (1, 19, 'Bricolage & Outillage', NOW())");
        $this->addSql("INSERT INTO category (created_by_id, id, name, created_at) VALUES (1, 20, 'Jardin & Extérieur', NOW())");
        $this->addSql("INSERT INTO category (created_by_id, id, name, created_at) VALUES (1, 21, 'Papeterie & Bureau', NOW())");
        $this->addSql("INSERT INTO category (created_by_id, id, name, created_at) VALUES (1, 22, 'Animalerie / Produits pour animaux', NOW())");
        $this->addSql("INSERT INTO category (created_by_id, id, name, created_at) VALUES (1, 23, 'Alimentation & Épicerie', NOW())");
        $this->addSql("INSERT INTO category (created_by_id, id, name, created_at) VALUES (1, 24, 'Produits de nettoyage', NOW())");
        $this->addSql("INSERT INTO category (created_by_id, id, name, created_at) VALUES (1, 25, 'Décoration & Mobilier', NOW())");
        $this->addSql("INSERT INTO category (created_by_id, id, name, created_at) VALUES (1, 26, 'Luminaires & Éclairage', NOW())");
        $this->addSql("INSERT INTO category (created_by_id, id, name, created_at) VALUES (1, 27, 'Arts & Loisirs créatifs', NOW())");
        $this->addSql("INSERT INTO category (created_by_id, id, name, created_at) VALUES (1, 28, 'Parfum', NOW())");
        $this->addSql("INSERT INTO category (created_by_id, id, name, created_at) VALUES (1, 29, 'Beauté & Parfum', NOW())");
        $this->addSql("INSERT INTO category (created_by_id, id, name, created_at) VALUES (1, 30, 'Cosmétique', NOW())");

    }

    public function down(Schema $schema): void
    {
        $this->addSql("DELETE FROM category WHERE id BETWEEN 1 AND 27");
    }
}
