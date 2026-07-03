<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

final class Version20260619103000 extends AbstractMigration
{
    public function getDescription(): string
    {
        return 'Ajoute les images et les donnees de demonstration pour le catalogue.';
    }

    public function up(Schema $schema): void
    {
        if ($schema->hasTable('figurine') && !$schema->getTable('figurine')->hasColumn('image')) {
            $this->addSql('ALTER TABLE figurine ADD image VARCHAR(255) DEFAULT NULL');
        }

        $this->addSql("INSERT INTO figurine (nom, prix_base, description, image)
            SELECT 'Samourai Imperial', 69, 'Figurine inspiree des armures japonaises traditionnelles, ideale pour une collection manga.', 'figurine-samourai-imperial.png'
            WHERE NOT EXISTS (SELECT 1 FROM figurine WHERE nom = 'Samourai Imperial')");

        $this->addSql("INSERT INTO figurine (nom, prix_base, description, image)
            SELECT 'Shogun Elegant', 59, 'Figurine elegante avec une tenue de shogun, sobre et detaillee.', 'figurine-shogun-elegant.png'
            WHERE NOT EXISTS (SELECT 1 FROM figurine WHERE nom = 'Shogun Elegant')");

        $this->addSql("INSERT INTO figurine (nom, prix_base, description, image)
            SELECT 'Figurine Moderne Classique', 49, 'Figurine moderne personnalisable avec plusieurs styles de cheveux et accessoires.', 'preview-v3-figurine-base.png'
            WHERE NOT EXISTS (SELECT 1 FROM figurine WHERE nom = 'Figurine Moderne Classique')");

        $this->addSql("INSERT INTO figurine (nom, prix_base, description, image)
            SELECT 'Costume Premium', 64, 'Figurine premium avec costume personnalise et rendu propre pour une boutique e-commerce.', 'figurine-costume-premium.png'
            WHERE NOT EXISTS (SELECT 1 FROM figurine WHERE nom = 'Costume Premium')");

        $this->addSql("INSERT INTO personnalisation (nom, image, prix, figurine_id)
            SELECT 'Cheveux Moderne 1', 'Hair1.png', 20, f.id FROM figurine f
            WHERE f.nom = 'Figurine Moderne Classique'
            AND NOT EXISTS (SELECT 1 FROM personnalisation WHERE nom = 'Cheveux Moderne 1')");

        $this->addSql("INSERT INTO personnalisation (nom, image, prix, figurine_id)
            SELECT 'Armure Shogun', 'Shogun2.png', 40, f.id FROM figurine f
            WHERE f.nom = 'Shogun Elegant'
            AND NOT EXISTS (SELECT 1 FROM personnalisation WHERE nom = 'Armure Shogun')");

        $this->addSql("INSERT INTO personnalisation (nom, image, prix, figurine_id)
            SELECT 'Armure Imperiale', 'imperiale3.png', 45, f.id FROM figurine f
            WHERE f.nom = 'Samourai Imperial'
            AND NOT EXISTS (SELECT 1 FROM personnalisation WHERE nom = 'Armure Imperiale')");

        $this->addSql("INSERT INTO personnalisation (nom, image, prix, figurine_id)
            SELECT 'Chapeau Traditionnel', 'chapeau1.png', 15, f.id FROM figurine f
            WHERE f.nom = 'Costume Premium'
            AND NOT EXISTS (SELECT 1 FROM personnalisation WHERE nom = 'Chapeau Traditionnel')");
    }

    public function down(Schema $schema): void
    {
        $this->addSql("DELETE FROM personnalisation WHERE nom IN ('Cheveux Moderne 1', 'Armure Shogun', 'Armure Imperiale', 'Chapeau Traditionnel')");
        $this->addSql("DELETE FROM figurine WHERE nom IN ('Samourai Imperial', 'Shogun Elegant', 'Figurine Moderne Classique', 'Costume Premium')");
    }
}
