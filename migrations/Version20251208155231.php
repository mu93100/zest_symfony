<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20251208155231 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE adhesion_motivation (adhesion_id INT NOT NULL, motivation_id INT NOT NULL, INDEX IDX_690EC6E4F68139D7 (adhesion_id), INDEX IDX_690EC6E48EDBCD4E (motivation_id), PRIMARY KEY (adhesion_id, motivation_id)) DEFAULT CHARACTER SET utf8mb4');
        $this->addSql('CREATE TABLE adhesion_participation_dispo (adhesion_id INT NOT NULL, participation_dispo_id INT NOT NULL, INDEX IDX_9764DC94F68139D7 (adhesion_id), INDEX IDX_9764DC944DA4905E (participation_dispo_id), PRIMARY KEY (adhesion_id, participation_dispo_id)) DEFAULT CHARACTER SET utf8mb4');
        $this->addSql('CREATE TABLE adhesion_pole (adhesion_id INT NOT NULL, pole_id INT NOT NULL, INDEX IDX_5717926F68139D7 (adhesion_id), INDEX IDX_5717926419C3385 (pole_id), PRIMARY KEY (adhesion_id, pole_id)) DEFAULT CHARACTER SET utf8mb4');
        $this->addSql('CREATE TABLE montant_adhesion (id INT AUTO_INCREMENT NOT NULL, montant INT NOT NULL, libelle VARCHAR(100) NOT NULL, PRIMARY KEY (id)) DEFAULT CHARACTER SET utf8mb4');
        $this->addSql('CREATE TABLE saison (id INT AUTO_INCREMENT NOT NULL, nom VARCHAR(9) NOT NULL, date_creation DATETIME NOT NULL, UNIQUE INDEX UNIQ_C0D0D5866C6E55B5 (nom), PRIMARY KEY (id)) DEFAULT CHARACTER SET utf8mb4');
        $this->addSql('ALTER TABLE adhesion_motivation ADD CONSTRAINT FK_690EC6E4F68139D7 FOREIGN KEY (adhesion_id) REFERENCES adhesion (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE adhesion_motivation ADD CONSTRAINT FK_690EC6E48EDBCD4E FOREIGN KEY (motivation_id) REFERENCES motivation (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE adhesion_participation_dispo ADD CONSTRAINT FK_9764DC94F68139D7 FOREIGN KEY (adhesion_id) REFERENCES adhesion (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE adhesion_participation_dispo ADD CONSTRAINT FK_9764DC944DA4905E FOREIGN KEY (participation_dispo_id) REFERENCES participation_dispo (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE adhesion_pole ADD CONSTRAINT FK_5717926F68139D7 FOREIGN KEY (adhesion_id) REFERENCES adhesion (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE adhesion_pole ADD CONSTRAINT FK_5717926419C3385 FOREIGN KEY (pole_id) REFERENCES pole (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE user_motivation DROP FOREIGN KEY `FK_4707B5018EDBCD4E`');
        $this->addSql('ALTER TABLE user_motivation DROP FOREIGN KEY `FK_4707B501A76ED395`');
        $this->addSql('DROP TABLE user_motivation');
        $this->addSql('ALTER TABLE adhesion ADD date_adhesion DATETIME NOT NULL, ADD attentes_texte LONGTEXT DEFAULT NULL, ADD competences_texte LONGTEXT DEFAULT NULL, ADD paiement TINYINT(1) NOT NULL, ADD user_id INT DEFAULT NULL, ADD groupe_id INT DEFAULT NULL, ADD montant_adhesion_id INT DEFAULT NULL, ADD saison_id INT NOT NULL, DROP libelle');
        $this->addSql('ALTER TABLE adhesion ADD CONSTRAINT FK_C50CA65AA76ED395 FOREIGN KEY (user_id) REFERENCES user (id)');
        $this->addSql('ALTER TABLE adhesion ADD CONSTRAINT FK_C50CA65A7A45358C FOREIGN KEY (groupe_id) REFERENCES groupe (id)');
        $this->addSql('ALTER TABLE adhesion ADD CONSTRAINT FK_C50CA65ABBAAE0A4 FOREIGN KEY (montant_adhesion_id) REFERENCES montant_adhesion (id)');
        $this->addSql('ALTER TABLE adhesion ADD CONSTRAINT FK_C50CA65AF965414C FOREIGN KEY (saison_id) REFERENCES saison (id)');
        $this->addSql('CREATE INDEX IDX_C50CA65AA76ED395 ON adhesion (user_id)');
        $this->addSql('CREATE INDEX IDX_C50CA65A7A45358C ON adhesion (groupe_id)');
        $this->addSql('CREATE INDEX IDX_C50CA65ABBAAE0A4 ON adhesion (montant_adhesion_id)');
        $this->addSql('CREATE INDEX IDX_C50CA65AF965414C ON adhesion (saison_id)');
        $this->addSql('ALTER TABLE producteurice_produit ADD CONSTRAINT FK_FCA015B5EE5BE958 FOREIGN KEY (producteurice_id) REFERENCES producteurice (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE producteurice_produit ADD CONSTRAINT FK_FCA015B5F347EFB FOREIGN KEY (produit_id) REFERENCES produit (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE reset_password_request ADD CONSTRAINT FK_7CE748AA76ED395 FOREIGN KEY (user_id) REFERENCES user (id)');
        $this->addSql('ALTER TABLE user DROP FOREIGN KEY `FK_8D93D6494DA4905E`');
        $this->addSql('ALTER TABLE user DROP FOREIGN KEY `FK_8D93D649F68139D7`');
        $this->addSql('DROP INDEX IDX_8D93D649F68139D7 ON user');
        $this->addSql('DROP INDEX IDX_8D93D6494DA4905E ON user');
        $this->addSql('ALTER TABLE user DROP motivations_attentes, DROP adhesion_id, DROP participation_dispo_id');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE user_motivation (user_id INT NOT NULL, motivation_id INT NOT NULL, INDEX IDX_4707B5018EDBCD4E (motivation_id), INDEX IDX_4707B501A76ED395 (user_id), PRIMARY KEY (user_id, motivation_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_0900_ai_ci` ENGINE = InnoDB COMMENT = \'\' ');
        $this->addSql('ALTER TABLE user_motivation ADD CONSTRAINT `FK_4707B5018EDBCD4E` FOREIGN KEY (motivation_id) REFERENCES motivation (id) ON UPDATE NO ACTION ON DELETE CASCADE');
        $this->addSql('ALTER TABLE user_motivation ADD CONSTRAINT `FK_4707B501A76ED395` FOREIGN KEY (user_id) REFERENCES user (id) ON UPDATE NO ACTION ON DELETE CASCADE');
        $this->addSql('ALTER TABLE adhesion_motivation DROP FOREIGN KEY FK_690EC6E4F68139D7');
        $this->addSql('ALTER TABLE adhesion_motivation DROP FOREIGN KEY FK_690EC6E48EDBCD4E');
        $this->addSql('ALTER TABLE adhesion_participation_dispo DROP FOREIGN KEY FK_9764DC94F68139D7');
        $this->addSql('ALTER TABLE adhesion_participation_dispo DROP FOREIGN KEY FK_9764DC944DA4905E');
        $this->addSql('ALTER TABLE adhesion_pole DROP FOREIGN KEY FK_5717926F68139D7');
        $this->addSql('ALTER TABLE adhesion_pole DROP FOREIGN KEY FK_5717926419C3385');
        $this->addSql('DROP TABLE adhesion_motivation');
        $this->addSql('DROP TABLE adhesion_participation_dispo');
        $this->addSql('DROP TABLE adhesion_pole');
        $this->addSql('DROP TABLE montant_adhesion');
        $this->addSql('DROP TABLE saison');
        $this->addSql('ALTER TABLE adhesion DROP FOREIGN KEY FK_C50CA65AA76ED395');
        $this->addSql('ALTER TABLE adhesion DROP FOREIGN KEY FK_C50CA65A7A45358C');
        $this->addSql('ALTER TABLE adhesion DROP FOREIGN KEY FK_C50CA65ABBAAE0A4');
        $this->addSql('ALTER TABLE adhesion DROP FOREIGN KEY FK_C50CA65AF965414C');
        $this->addSql('DROP INDEX IDX_C50CA65AA76ED395 ON adhesion');
        $this->addSql('DROP INDEX IDX_C50CA65A7A45358C ON adhesion');
        $this->addSql('DROP INDEX IDX_C50CA65ABBAAE0A4 ON adhesion');
        $this->addSql('DROP INDEX IDX_C50CA65AF965414C ON adhesion');
        $this->addSql('ALTER TABLE adhesion ADD libelle VARCHAR(100) NOT NULL, DROP date_adhesion, DROP attentes_texte, DROP competences_texte, DROP paiement, DROP user_id, DROP groupe_id, DROP montant_adhesion_id, DROP saison_id');
        $this->addSql('ALTER TABLE producteurice_produit DROP FOREIGN KEY FK_FCA015B5EE5BE958');
        $this->addSql('ALTER TABLE producteurice_produit DROP FOREIGN KEY FK_FCA015B5F347EFB');
        $this->addSql('ALTER TABLE reset_password_request DROP FOREIGN KEY FK_7CE748AA76ED395');
        $this->addSql('ALTER TABLE user ADD motivations_attentes LONGTEXT DEFAULT NULL, ADD adhesion_id INT NOT NULL, ADD participation_dispo_id INT NOT NULL');
        $this->addSql('ALTER TABLE user ADD CONSTRAINT `FK_8D93D6494DA4905E` FOREIGN KEY (participation_dispo_id) REFERENCES participation_dispo (id) ON UPDATE NO ACTION ON DELETE NO ACTION');
        $this->addSql('ALTER TABLE user ADD CONSTRAINT `FK_8D93D649F68139D7` FOREIGN KEY (adhesion_id) REFERENCES adhesion (id) ON UPDATE NO ACTION ON DELETE NO ACTION');
        $this->addSql('CREATE INDEX IDX_8D93D649F68139D7 ON user (adhesion_id)');
        $this->addSql('CREATE INDEX IDX_8D93D6494DA4905E ON user (participation_dispo_id)');
    }
}
