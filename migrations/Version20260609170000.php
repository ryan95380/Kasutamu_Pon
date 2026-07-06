<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

final class Version20260609170000 extends AbstractMigration
{
    public function getDescription(): string
    {
        return 'Ajoute les images des figurines et recree les donnees de demonstration.';
    }

    public function up(Schema $schema): void
    {
        if ($schema->hasTable('figurine') && !$schema->getTable('figurine')->hasColumn('image')) {
            $this->addSql('ALTER TABLE figurine ADD image VARCHAR(255) DEFAULT NULL');
        }

        $password = '$2y$12$Lu1uWw83jZzMZfLrphrYx.Yh2hvXpTnaa2.UAG2AE7T90fhE8ooR2';

        $this->addSql("INSERT INTO figurine (nom, prix_base, description, image)
            SELECT 'Figurine Moderne Classique', 39, 'Figurine manga personnalisable avec tenue moderne et style classique.', 'preview-v3-figurine-base.png'
            WHERE NOT EXISTS (SELECT 1 FROM figurine WHERE nom = 'Figurine Moderne Classique')");
        $this->addSql("INSERT INTO figurine (nom, prix_base, description, image)
            SELECT 'Samourai Imperial', 55, 'Figurine inspiree des armures japonaises, ideale pour une personnalisation premium.', 'figurine-samourai-imperial.png'
            WHERE NOT EXISTS (SELECT 1 FROM figurine WHERE nom = 'Samourai Imperial')");
        $this->addSql("INSERT INTO figurine (nom, prix_base, description, image)
            SELECT 'Shogun Elegant', 49, 'Figurine manga elegante avec silhouette de shogun et finition detaillee.', 'figurine-shogun-elegant.png'
            WHERE NOT EXISTS (SELECT 1 FROM figurine WHERE nom = 'Shogun Elegant')");
        $this->addSql("INSERT INTO figurine (nom, prix_base, description, image)
            SELECT 'Costume Premium', 52, 'Figurine personnalisee avec costume premium pour un rendu moderne.', 'figurine-costume-premium.png'
            WHERE NOT EXISTS (SELECT 1 FROM figurine WHERE nom = 'Costume Premium')");

        $this->addSql("INSERT INTO personnalisation (nom, image, prix, figurine_id)
            SELECT 'Cheveux Moderne 1', 'figurine-moderne-classique1.png', 10, id FROM figurine
            WHERE nom = 'Figurine Moderne Classique'
            AND NOT EXISTS (SELECT 1 FROM personnalisation WHERE nom = 'Cheveux Moderne 1')");
        $this->addSql("INSERT INTO personnalisation (nom, image, prix, figurine_id)
            SELECT 'Costume Premium', 'Figurine_costume1.png', 20, id FROM figurine
            WHERE nom = 'Figurine Moderne Classique'
            AND NOT EXISTS (SELECT 1 FROM personnalisation WHERE nom = 'Costume Premium')");
        $this->addSql("INSERT INTO personnalisation (nom, image, prix, figurine_id)
            SELECT 'Armure Shogun', 'figurine-shogun-elegant.png', 25, id FROM figurine
            WHERE nom = 'Shogun Elegant'
            AND NOT EXISTS (SELECT 1 FROM personnalisation WHERE nom = 'Armure Shogun')");
        $this->addSql("INSERT INTO personnalisation (nom, image, prix, figurine_id)
            SELECT 'Chapeau Traditionnel', 'figurine_chapeau1.png', 8, id FROM figurine
            WHERE nom = 'Samourai Imperial'
            AND NOT EXISTS (SELECT 1 FROM personnalisation WHERE nom = 'Chapeau Traditionnel')");

        $this->addSql("INSERT INTO utilisateur (nom, prenom, email, mot_de_passe, roles)
            SELECT 'Admin', 'Demo', 'admin@kasutamupon.test', '{$password}', '[\"ROLE_ADMIN\"]'
            WHERE NOT EXISTS (SELECT 1 FROM utilisateur WHERE email = 'admin@kasutamupon.test')");
        $this->addSql("INSERT INTO utilisateur (nom, prenom, email, mot_de_passe, roles)
            SELECT 'Client', 'Demo', 'client@kasutamupon.test', '{$password}', '[]'
            WHERE NOT EXISTS (SELECT 1 FROM utilisateur WHERE email = 'client@kasutamupon.test')");

        $this->addSql("INSERT INTO commande (date_commande, statut, utilisateur_id, personnalisation_id)
            SELECT CURRENT_DATE, 'Payee', u.id, p.id
            FROM utilisateur u, personnalisation p
            WHERE u.email = 'client@kasutamupon.test'
            AND p.nom = 'Cheveux Moderne 1'
            AND NOT EXISTS (
                SELECT 1 FROM commande c
                WHERE c.utilisateur_id = u.id AND c.personnalisation_id = p.id AND c.statut = 'Payee'
            )");
        $this->addSql("INSERT INTO commande (date_commande, statut, utilisateur_id, personnalisation_id)
            SELECT CURRENT_DATE, 'En preparation', u.id, p.id
            FROM utilisateur u, personnalisation p
            WHERE u.email = 'client@kasutamupon.test'
            AND p.nom = 'Armure Shogun'
            AND NOT EXISTS (
                SELECT 1 FROM commande c
                WHERE c.utilisateur_id = u.id AND c.personnalisation_id = p.id AND c.statut = 'En preparation'
            )");

        $this->addSql("INSERT INTO paiement (montant, mode_paiement, commande_id)
            SELECT 49, 'Carte bancaire', c.id
            FROM commande c
            JOIN utilisateur u ON u.id = c.utilisateur_id
            JOIN personnalisation p ON p.id = c.personnalisation_id
            WHERE u.email = 'client@kasutamupon.test' AND p.nom = 'Cheveux Moderne 1'
            AND NOT EXISTS (SELECT 1 FROM paiement pa WHERE pa.commande_id = c.id)");
        $this->addSql("INSERT INTO paiement (montant, mode_paiement, commande_id)
            SELECT 74, 'PayPal', c.id
            FROM commande c
            JOIN utilisateur u ON u.id = c.utilisateur_id
            JOIN personnalisation p ON p.id = c.personnalisation_id
            WHERE u.email = 'client@kasutamupon.test' AND p.nom = 'Armure Shogun'
            AND NOT EXISTS (SELECT 1 FROM paiement pa WHERE pa.commande_id = c.id)");
    }

    public function down(Schema $schema): void
    {
        $this->addSql("DELETE pa FROM paiement pa
            JOIN commande c ON c.id = pa.commande_id
            JOIN utilisateur u ON u.id = c.utilisateur_id
            WHERE u.email IN ('admin@kasutamupon.test', 'client@kasutamupon.test')");
        $this->addSql("DELETE c FROM commande c
            JOIN utilisateur u ON u.id = c.utilisateur_id
            WHERE u.email IN ('admin@kasutamupon.test', 'client@kasutamupon.test')");
        $this->addSql("DELETE FROM utilisateur WHERE email IN ('admin@kasutamupon.test', 'client@kasutamupon.test')");
        $this->addSql("DELETE FROM personnalisation WHERE nom IN ('Cheveux Moderne 1', 'Costume Premium', 'Armure Shogun', 'Chapeau Traditionnel')");
        $this->addSql("DELETE FROM figurine WHERE nom IN ('Figurine Moderne Classique', 'Samourai Imperial', 'Shogun Elegant', 'Costume Premium')");
    }
}
