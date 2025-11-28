<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20251128102621 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE producteurice (id INT AUTO_INCREMENT NOT NULL, nom VARCHAR(100) NOT NULL, produits VARCHAR(255) NOT NULL, is_coop TINYINT(1) NOT NULL, site VARCHAR(255) NOT NULL, lien_produits VARCHAR(255) DEFAULT NULL, logo VARCHAR(255) NOT NULL, description LONGTEXT NOT NULL, PRIMARY KEY (id)) DEFAULT CHARACTER SET utf8mb4');
        $this->addSql('CREATE TABLE producteurice_produit (producteurice_id INT NOT NULL, produit_id INT NOT NULL, INDEX IDX_FCA015B5EE5BE958 (producteurice_id), INDEX IDX_FCA015B5F347EFB (produit_id), PRIMARY KEY (producteurice_id, produit_id)) DEFAULT CHARACTER SET utf8mb4');
        $this->addSql('CREATE TABLE reset_password_request (id INT AUTO_INCREMENT NOT NULL, selector VARCHAR(20) NOT NULL, hashed_token VARCHAR(100) NOT NULL, requested_at DATETIME NOT NULL, expires_at DATETIME NOT NULL, user_id INT NOT NULL, INDEX IDX_7CE748AA76ED395 (user_id), PRIMARY KEY (id)) DEFAULT CHARACTER SET utf8mb4');
        $this->addSql('ALTER TABLE producteurice_produit ADD CONSTRAINT FK_FCA015B5EE5BE958 FOREIGN KEY (producteurice_id) REFERENCES producteurice (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE producteurice_produit ADD CONSTRAINT FK_FCA015B5F347EFB FOREIGN KEY (produit_id) REFERENCES produit (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE reset_password_request ADD CONSTRAINT FK_7CE748AA76ED395 FOREIGN KEY (user_id) REFERENCES user (id)');
        $this->addSql('ALTER TABLE producteur_produit DROP FOREIGN KEY `FK_4CD25379AB9BB300`');
        $this->addSql('ALTER TABLE producteur_produit DROP FOREIGN KEY `FK_4CD25379F347EFB`');
        $this->addSql('DROP TABLE producteur');
        $this->addSql('DROP TABLE producteur_produit');
        $this->addSql('DROP TABLE userm');
        $this->addSql('DROP TABLE userm_motivation');
        $this->addSql('DROP TABLE userm_pole');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE producteur (id INT AUTO_INCREMENT NOT NULL, nom VARCHAR(100) CHARACTER SET utf8mb4 NOT NULL COLLATE `utf8mb4_0900_ai_ci`, produits VARCHAR(255) CHARACTER SET utf8mb4 NOT NULL COLLATE `utf8mb4_0900_ai_ci`, is_coop TINYINT(1) NOT NULL, site VARCHAR(255) CHARACTER SET utf8mb4 NOT NULL COLLATE `utf8mb4_0900_ai_ci`, lien_produits VARCHAR(255) CHARACTER SET utf8mb4 DEFAULT NULL COLLATE `utf8mb4_0900_ai_ci`, logo VARCHAR(255) CHARACTER SET utf8mb4 NOT NULL COLLATE `utf8mb4_0900_ai_ci`, description LONGTEXT CHARACTER SET utf8mb4 NOT NULL COLLATE `utf8mb4_0900_ai_ci`, PRIMARY KEY (id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_0900_ai_ci` ENGINE = InnoDB COMMENT = \'\' ');
        $this->addSql('CREATE TABLE producteur_produit (producteur_id INT NOT NULL, produit_id INT NOT NULL, INDEX IDX_4CD25379AB9BB300 (producteur_id), INDEX IDX_4CD25379F347EFB (produit_id), PRIMARY KEY (producteur_id, produit_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_0900_ai_ci` ENGINE = InnoDB COMMENT = \'\' ');
        $this->addSql('CREATE TABLE userm (id INT AUTO_INCREMENT NOT NULL, nom VARCHAR(45) CHARACTER SET utf8mb4 NOT NULL COLLATE `utf8mb4_0900_ai_ci`, prenom VARCHAR(45) CHARACTER SET utf8mb4 NOT NULL COLLATE `utf8mb4_0900_ai_ci`, email VARCHAR(180) CHARACTER SET utf8mb4 NOT NULL COLLATE `utf8mb4_0900_ai_ci`, mot_de_passe VARCHAR(255) CHARACTER SET utf8mb4 NOT NULL COLLATE `utf8mb4_0900_ai_ci`, is_admin TINYINT(1) NOT NULL, telephone INT NOT NULL, adresse VARCHAR(255) CHARACTER SET utf8mb4 NOT NULL COLLATE `utf8mb4_0900_ai_ci`, code_postal INT NOT NULL, ville VARCHAR(100) CHARACTER SET utf8mb4 NOT NULL COLLATE `utf8mb4_0900_ai_ci`, date_de_naissance DATE DEFAULT NULL, composition_foyer INT DEFAULT NULL, nombre_enfants INT DEFAULT NULL, is_referent TINYINT(1) NOT NULL, motivations_attentes LONGTEXT CHARACTER SET utf8mb4 DEFAULT NULL COLLATE `utf8mb4_0900_ai_ci`, participation_dispo INT NOT NULL, competences LONGTEXT CHARACTER SET utf8mb4 DEFAULT NULL COLLATE `utf8mb4_0900_ai_ci`, montant_adhesion INT NOT NULL, groupe_id INT DEFAULT NULL, INDEX IDX_EE8C988A7A45358C (groupe_id), PRIMARY KEY (id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_0900_ai_ci` ENGINE = MyISAM COMMENT = \'\' ');
        $this->addSql('CREATE TABLE userm_motivation (userm_id INT NOT NULL, motivation_id INT NOT NULL, INDEX IDX_D222E29B8EDBCD4E (motivation_id), INDEX IDX_D222E29BD7759491 (userm_id), PRIMARY KEY (userm_id, motivation_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_0900_ai_ci` ENGINE = MyISAM COMMENT = \'\' ');
        $this->addSql('CREATE TABLE userm_pole (userm_id INT NOT NULL, pole_id INT NOT NULL, INDEX IDX_B356F04419C3385 (pole_id), INDEX IDX_B356F04D7759491 (userm_id), PRIMARY KEY (userm_id, pole_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_0900_ai_ci` ENGINE = MyISAM COMMENT = \'\' ');
        $this->addSql('ALTER TABLE producteur_produit ADD CONSTRAINT `FK_4CD25379AB9BB300` FOREIGN KEY (producteur_id) REFERENCES producteur (id) ON UPDATE NO ACTION ON DELETE CASCADE');
        $this->addSql('ALTER TABLE producteur_produit ADD CONSTRAINT `FK_4CD25379F347EFB` FOREIGN KEY (produit_id) REFERENCES produit (id) ON UPDATE NO ACTION ON DELETE CASCADE');
        $this->addSql('ALTER TABLE producteurice_produit DROP FOREIGN KEY FK_FCA015B5EE5BE958');
        $this->addSql('ALTER TABLE producteurice_produit DROP FOREIGN KEY FK_FCA015B5F347EFB');
        $this->addSql('ALTER TABLE reset_password_request DROP FOREIGN KEY FK_7CE748AA76ED395');
        $this->addSql('DROP TABLE producteurice');
        $this->addSql('DROP TABLE producteurice_produit');
        $this->addSql('DROP TABLE reset_password_request');
    }
}
