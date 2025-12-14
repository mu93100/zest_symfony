<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20251214111702 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE adhesion (id INT AUTO_INCREMENT NOT NULL, date_adhesion DATETIME NOT NULL, attentes_texte LONGTEXT DEFAULT NULL, competences_texte LONGTEXT DEFAULT NULL, paiement TINYINT(1) NOT NULL, user_id INT DEFAULT NULL, groupe_id INT DEFAULT NULL, montant_adhesion_id INT DEFAULT NULL, saison_id INT NOT NULL, INDEX IDX_C50CA65AA76ED395 (user_id), INDEX IDX_C50CA65A7A45358C (groupe_id), INDEX IDX_C50CA65ABBAAE0A4 (montant_adhesion_id), INDEX IDX_C50CA65AF965414C (saison_id), PRIMARY KEY (id)) DEFAULT CHARACTER SET utf8mb4');
        $this->addSql('CREATE TABLE adhesion_motivation (adhesion_id INT NOT NULL, motivation_id INT NOT NULL, INDEX IDX_690EC6E4F68139D7 (adhesion_id), INDEX IDX_690EC6E48EDBCD4E (motivation_id), PRIMARY KEY (adhesion_id, motivation_id)) DEFAULT CHARACTER SET utf8mb4');
        $this->addSql('CREATE TABLE adhesion_dispo (adhesion_id INT NOT NULL, dispo_id INT NOT NULL, INDEX IDX_4DC30B07F68139D7 (adhesion_id), INDEX IDX_4DC30B07A18C1CC9 (dispo_id), PRIMARY KEY (adhesion_id, dispo_id)) DEFAULT CHARACTER SET utf8mb4');
        $this->addSql('CREATE TABLE adhesion_pole (adhesion_id INT NOT NULL, pole_id INT NOT NULL, INDEX IDX_5717926F68139D7 (adhesion_id), INDEX IDX_5717926419C3385 (pole_id), PRIMARY KEY (adhesion_id, pole_id)) DEFAULT CHARACTER SET utf8mb4');
        $this->addSql('CREATE TABLE categorie (id INT AUTO_INCREMENT NOT NULL, nom VARCHAR(100) NOT NULL, PRIMARY KEY (id)) DEFAULT CHARACTER SET utf8mb4');
        $this->addSql('CREATE TABLE dispo (id INT AUTO_INCREMENT NOT NULL, libelle_dispo VARCHAR(200) NOT NULL, PRIMARY KEY (id)) DEFAULT CHARACTER SET utf8mb4');
        $this->addSql('CREATE TABLE groupe (id INT AUTO_INCREMENT NOT NULL, nom VARCHAR(45) NOT NULL, ville VARCHAR(45) NOT NULL, is_referent TINYINT(1) NOT NULL, is_open TINYINT(1) NOT NULL, date_creation DATETIME NOT NULL, PRIMARY KEY (id)) DEFAULT CHARACTER SET utf8mb4');
        $this->addSql('CREATE TABLE montant_adhesion (id INT AUTO_INCREMENT NOT NULL, montant INT NOT NULL, libelle VARCHAR(100) NOT NULL, PRIMARY KEY (id)) DEFAULT CHARACTER SET utf8mb4');
        $this->addSql('CREATE TABLE motivation (id INT AUTO_INCREMENT NOT NULL, libelle VARCHAR(255) NOT NULL, PRIMARY KEY (id)) DEFAULT CHARACTER SET utf8mb4');
        $this->addSql('CREATE TABLE photos (id INT AUTO_INCREMENT NOT NULL, description VARCHAR(255) NOT NULL, ressource_id INT DEFAULT NULL, INDEX IDX_876E0D9FC6CD52A (ressource_id), PRIMARY KEY (id)) DEFAULT CHARACTER SET utf8mb4');
        $this->addSql('CREATE TABLE pole (id INT AUTO_INCREMENT NOT NULL, nom VARCHAR(100) NOT NULL, descriptif LONGTEXT NOT NULL, descriptif_pdf VARCHAR(255) NOT NULL, volume_horaire INT NOT NULL, PRIMARY KEY (id)) DEFAULT CHARACTER SET utf8mb4');
        $this->addSql('CREATE TABLE producteurice (id INT AUTO_INCREMENT NOT NULL, nom VARCHAR(100) NOT NULL, produits VARCHAR(255) NOT NULL, is_coop TINYINT(1) NOT NULL, site VARCHAR(255) NOT NULL, lien_produits VARCHAR(255) DEFAULT NULL, logo VARCHAR(255) NOT NULL, description LONGTEXT NOT NULL, PRIMARY KEY (id)) DEFAULT CHARACTER SET utf8mb4');
        $this->addSql('CREATE TABLE producteurice_produit (producteurice_id INT NOT NULL, produit_id INT NOT NULL, INDEX IDX_FCA015B5EE5BE958 (producteurice_id), INDEX IDX_FCA015B5F347EFB (produit_id), PRIMARY KEY (producteurice_id, produit_id)) DEFAULT CHARACTER SET utf8mb4');
        $this->addSql('CREATE TABLE produit (id INT AUTO_INCREMENT NOT NULL, nom VARCHAR(100) NOT NULL, description LONGTEXT NOT NULL, photo VARCHAR(255) NOT NULL, PRIMARY KEY (id)) DEFAULT CHARACTER SET utf8mb4');
        $this->addSql('CREATE TABLE recette (id INT AUTO_INCREMENT NOT NULL, date_publication DATETIME NOT NULL, titre VARCHAR(255) NOT NULL, nombre_mangeurs INT NOT NULL, ingredients VARCHAR(500) NOT NULL, description LONGTEXT NOT NULL, photo VARCHAR(255) NOT NULL, auteurice_id INT NOT NULL, INDEX IDX_49BB639055D7EF5A (auteurice_id), PRIMARY KEY (id)) DEFAULT CHARACTER SET utf8mb4');
        $this->addSql('CREATE TABLE recette_produit (recette_id INT NOT NULL, produit_id INT NOT NULL, INDEX IDX_EDDD365D89312FE9 (recette_id), INDEX IDX_EDDD365DF347EFB (produit_id), PRIMARY KEY (recette_id, produit_id)) DEFAULT CHARACTER SET utf8mb4');
        $this->addSql('CREATE TABLE reset_password_request (id INT AUTO_INCREMENT NOT NULL, selector VARCHAR(20) NOT NULL, hashed_token VARCHAR(100) NOT NULL, requested_at DATETIME NOT NULL, expires_at DATETIME NOT NULL, user_id INT NOT NULL, INDEX IDX_7CE748AA76ED395 (user_id), PRIMARY KEY (id)) DEFAULT CHARACTER SET utf8mb4');
        $this->addSql('CREATE TABLE ressource (id INT AUTO_INCREMENT NOT NULL, date_publication DATETIME NOT NULL, titre VARCHAR(150) NOT NULL, sous_titre VARCHAR(200) NOT NULL, ressource_texte LONGTEXT NOT NULL, lien_externe1 VARCHAR(500) DEFAULT NULL, lien_externe2 VARCHAR(500) DEFAULT NULL, lien_externe3 VARCHAR(500) DEFAULT NULL, statut VARCHAR(20) NOT NULL, categorie_id INT NOT NULL, pole_id INT DEFAULT NULL, user_id INT DEFAULT NULL, photo_principale_id INT DEFAULT NULL, INDEX IDX_939F4544BCF5E72D (categorie_id), INDEX IDX_939F4544419C3385 (pole_id), INDEX IDX_939F4544A76ED395 (user_id), UNIQUE INDEX UNIQ_939F454451C718BE (photo_principale_id), PRIMARY KEY (id)) DEFAULT CHARACTER SET utf8mb4');
        $this->addSql('CREATE TABLE saison (id INT AUTO_INCREMENT NOT NULL, nom VARCHAR(9) NOT NULL, date_creation DATETIME NOT NULL, UNIQUE INDEX UNIQ_C0D0D5866C6E55B5 (nom), PRIMARY KEY (id)) DEFAULT CHARACTER SET utf8mb4');
        $this->addSql('CREATE TABLE user (id INT AUTO_INCREMENT NOT NULL, email VARCHAR(180) NOT NULL, roles JSON NOT NULL, password VARCHAR(255) NOT NULL, nom VARCHAR(45) NOT NULL, prenom VARCHAR(45) NOT NULL, telephone VARCHAR(10) NOT NULL, adresse VARCHAR(255) DEFAULT NULL, code_postal VARCHAR(5) NOT NULL, ville VARCHAR(100) NOT NULL, date_de_naissance DATE DEFAULT NULL, composition_foyer INT UNSIGNED DEFAULT NULL, nombre_enfants INT UNSIGNED DEFAULT NULL, groupe_id INT DEFAULT NULL, INDEX IDX_8D93D6497A45358C (groupe_id), UNIQUE INDEX UNIQ_IDENTIFIER_EMAIL (email), PRIMARY KEY (id)) DEFAULT CHARACTER SET utf8mb4');
        $this->addSql('CREATE TABLE user_pole (user_id INT NOT NULL, pole_id INT NOT NULL, INDEX IDX_87E10E28A76ED395 (user_id), INDEX IDX_87E10E28419C3385 (pole_id), PRIMARY KEY (user_id, pole_id)) DEFAULT CHARACTER SET utf8mb4');
        $this->addSql('CREATE TABLE messenger_messages (id BIGINT AUTO_INCREMENT NOT NULL, body LONGTEXT NOT NULL, headers LONGTEXT NOT NULL, queue_name VARCHAR(190) NOT NULL, created_at DATETIME NOT NULL, available_at DATETIME NOT NULL, delivered_at DATETIME DEFAULT NULL, INDEX IDX_75EA56E0FB7336F0 (queue_name), INDEX IDX_75EA56E0E3BD61CE (available_at), INDEX IDX_75EA56E016BA31DB (delivered_at), PRIMARY KEY (id)) DEFAULT CHARACTER SET utf8mb4');
        $this->addSql('ALTER TABLE adhesion ADD CONSTRAINT FK_C50CA65AA76ED395 FOREIGN KEY (user_id) REFERENCES user (id)');
        $this->addSql('ALTER TABLE adhesion ADD CONSTRAINT FK_C50CA65A7A45358C FOREIGN KEY (groupe_id) REFERENCES groupe (id)');
        $this->addSql('ALTER TABLE adhesion ADD CONSTRAINT FK_C50CA65ABBAAE0A4 FOREIGN KEY (montant_adhesion_id) REFERENCES montant_adhesion (id)');
        $this->addSql('ALTER TABLE adhesion ADD CONSTRAINT FK_C50CA65AF965414C FOREIGN KEY (saison_id) REFERENCES saison (id)');
        $this->addSql('ALTER TABLE adhesion_motivation ADD CONSTRAINT FK_690EC6E4F68139D7 FOREIGN KEY (adhesion_id) REFERENCES adhesion (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE adhesion_motivation ADD CONSTRAINT FK_690EC6E48EDBCD4E FOREIGN KEY (motivation_id) REFERENCES motivation (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE adhesion_dispo ADD CONSTRAINT FK_4DC30B07F68139D7 FOREIGN KEY (adhesion_id) REFERENCES adhesion (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE adhesion_dispo ADD CONSTRAINT FK_4DC30B07A18C1CC9 FOREIGN KEY (dispo_id) REFERENCES dispo (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE adhesion_pole ADD CONSTRAINT FK_5717926F68139D7 FOREIGN KEY (adhesion_id) REFERENCES adhesion (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE adhesion_pole ADD CONSTRAINT FK_5717926419C3385 FOREIGN KEY (pole_id) REFERENCES pole (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE photos ADD CONSTRAINT FK_876E0D9FC6CD52A FOREIGN KEY (ressource_id) REFERENCES ressource (id)');
        $this->addSql('ALTER TABLE producteurice_produit ADD CONSTRAINT FK_FCA015B5EE5BE958 FOREIGN KEY (producteurice_id) REFERENCES producteurice (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE producteurice_produit ADD CONSTRAINT FK_FCA015B5F347EFB FOREIGN KEY (produit_id) REFERENCES produit (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE recette ADD CONSTRAINT FK_49BB639055D7EF5A FOREIGN KEY (auteurice_id) REFERENCES user (id)');
        $this->addSql('ALTER TABLE recette_produit ADD CONSTRAINT FK_EDDD365D89312FE9 FOREIGN KEY (recette_id) REFERENCES recette (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE recette_produit ADD CONSTRAINT FK_EDDD365DF347EFB FOREIGN KEY (produit_id) REFERENCES produit (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE reset_password_request ADD CONSTRAINT FK_7CE748AA76ED395 FOREIGN KEY (user_id) REFERENCES user (id)');
        $this->addSql('ALTER TABLE ressource ADD CONSTRAINT FK_939F4544BCF5E72D FOREIGN KEY (categorie_id) REFERENCES categorie (id)');
        $this->addSql('ALTER TABLE ressource ADD CONSTRAINT FK_939F4544419C3385 FOREIGN KEY (pole_id) REFERENCES pole (id)');
        $this->addSql('ALTER TABLE ressource ADD CONSTRAINT FK_939F4544A76ED395 FOREIGN KEY (user_id) REFERENCES user (id)');
        $this->addSql('ALTER TABLE ressource ADD CONSTRAINT FK_939F454451C718BE FOREIGN KEY (photo_principale_id) REFERENCES photos (id)');
        $this->addSql('ALTER TABLE user ADD CONSTRAINT FK_8D93D6497A45358C FOREIGN KEY (groupe_id) REFERENCES groupe (id)');
        $this->addSql('ALTER TABLE user_pole ADD CONSTRAINT FK_87E10E28A76ED395 FOREIGN KEY (user_id) REFERENCES user (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE user_pole ADD CONSTRAINT FK_87E10E28419C3385 FOREIGN KEY (pole_id) REFERENCES pole (id) ON DELETE CASCADE');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE adhesion DROP FOREIGN KEY FK_C50CA65AA76ED395');
        $this->addSql('ALTER TABLE adhesion DROP FOREIGN KEY FK_C50CA65A7A45358C');
        $this->addSql('ALTER TABLE adhesion DROP FOREIGN KEY FK_C50CA65ABBAAE0A4');
        $this->addSql('ALTER TABLE adhesion DROP FOREIGN KEY FK_C50CA65AF965414C');
        $this->addSql('ALTER TABLE adhesion_motivation DROP FOREIGN KEY FK_690EC6E4F68139D7');
        $this->addSql('ALTER TABLE adhesion_motivation DROP FOREIGN KEY FK_690EC6E48EDBCD4E');
        $this->addSql('ALTER TABLE adhesion_dispo DROP FOREIGN KEY FK_4DC30B07F68139D7');
        $this->addSql('ALTER TABLE adhesion_dispo DROP FOREIGN KEY FK_4DC30B07A18C1CC9');
        $this->addSql('ALTER TABLE adhesion_pole DROP FOREIGN KEY FK_5717926F68139D7');
        $this->addSql('ALTER TABLE adhesion_pole DROP FOREIGN KEY FK_5717926419C3385');
        $this->addSql('ALTER TABLE photos DROP FOREIGN KEY FK_876E0D9FC6CD52A');
        $this->addSql('ALTER TABLE producteurice_produit DROP FOREIGN KEY FK_FCA015B5EE5BE958');
        $this->addSql('ALTER TABLE producteurice_produit DROP FOREIGN KEY FK_FCA015B5F347EFB');
        $this->addSql('ALTER TABLE recette DROP FOREIGN KEY FK_49BB639055D7EF5A');
        $this->addSql('ALTER TABLE recette_produit DROP FOREIGN KEY FK_EDDD365D89312FE9');
        $this->addSql('ALTER TABLE recette_produit DROP FOREIGN KEY FK_EDDD365DF347EFB');
        $this->addSql('ALTER TABLE reset_password_request DROP FOREIGN KEY FK_7CE748AA76ED395');
        $this->addSql('ALTER TABLE ressource DROP FOREIGN KEY FK_939F4544BCF5E72D');
        $this->addSql('ALTER TABLE ressource DROP FOREIGN KEY FK_939F4544419C3385');
        $this->addSql('ALTER TABLE ressource DROP FOREIGN KEY FK_939F4544A76ED395');
        $this->addSql('ALTER TABLE ressource DROP FOREIGN KEY FK_939F454451C718BE');
        $this->addSql('ALTER TABLE user DROP FOREIGN KEY FK_8D93D6497A45358C');
        $this->addSql('ALTER TABLE user_pole DROP FOREIGN KEY FK_87E10E28A76ED395');
        $this->addSql('ALTER TABLE user_pole DROP FOREIGN KEY FK_87E10E28419C3385');
        $this->addSql('DROP TABLE adhesion');
        $this->addSql('DROP TABLE adhesion_motivation');
        $this->addSql('DROP TABLE adhesion_dispo');
        $this->addSql('DROP TABLE adhesion_pole');
        $this->addSql('DROP TABLE categorie');
        $this->addSql('DROP TABLE dispo');
        $this->addSql('DROP TABLE groupe');
        $this->addSql('DROP TABLE montant_adhesion');
        $this->addSql('DROP TABLE motivation');
        $this->addSql('DROP TABLE photos');
        $this->addSql('DROP TABLE pole');
        $this->addSql('DROP TABLE producteurice');
        $this->addSql('DROP TABLE producteurice_produit');
        $this->addSql('DROP TABLE produit');
        $this->addSql('DROP TABLE recette');
        $this->addSql('DROP TABLE recette_produit');
        $this->addSql('DROP TABLE reset_password_request');
        $this->addSql('DROP TABLE ressource');
        $this->addSql('DROP TABLE saison');
        $this->addSql('DROP TABLE user');
        $this->addSql('DROP TABLE user_pole');
        $this->addSql('DROP TABLE messenger_messages');
    }
}
