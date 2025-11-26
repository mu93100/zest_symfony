<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20251126155602 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE userm (id INT AUTO_INCREMENT NOT NULL, nom VARCHAR(45) NOT NULL, prenom VARCHAR(45) NOT NULL, email VARCHAR(180) NOT NULL, mot_de_passe VARCHAR(255) NOT NULL, is_admin TINYINT(1) NOT NULL, telephone INT NOT NULL, adresse VARCHAR(255) NOT NULL, code_postal INT NOT NULL, ville VARCHAR(100) NOT NULL, date_de_naissance DATE DEFAULT NULL, composition_foyer INT DEFAULT NULL, nombre_enfants INT DEFAULT NULL, is_referent TINYINT(1) NOT NULL, motivations_attentes LONGTEXT DEFAULT NULL, participation_dispo INT NOT NULL, competences LONGTEXT DEFAULT NULL, montant_adhesion INT NOT NULL, groupe_id INT DEFAULT NULL, INDEX IDX_EE8C988A7A45358C (groupe_id), PRIMARY KEY (id)) DEFAULT CHARACTER SET utf8mb4');
        $this->addSql('CREATE TABLE userm_pole (userm_id INT NOT NULL, pole_id INT NOT NULL, INDEX IDX_B356F04D7759491 (userm_id), INDEX IDX_B356F04419C3385 (pole_id), PRIMARY KEY (userm_id, pole_id)) DEFAULT CHARACTER SET utf8mb4');
        $this->addSql('CREATE TABLE userm_motivation (userm_id INT NOT NULL, motivation_id INT NOT NULL, INDEX IDX_D222E29BD7759491 (userm_id), INDEX IDX_D222E29B8EDBCD4E (motivation_id), PRIMARY KEY (userm_id, motivation_id)) DEFAULT CHARACTER SET utf8mb4');
        $this->addSql('ALTER TABLE userm ADD CONSTRAINT FK_EE8C988A7A45358C FOREIGN KEY (groupe_id) REFERENCES groupe (id)');
        $this->addSql('ALTER TABLE userm_pole ADD CONSTRAINT FK_B356F04D7759491 FOREIGN KEY (userm_id) REFERENCES userm (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE userm_pole ADD CONSTRAINT FK_B356F04419C3385 FOREIGN KEY (pole_id) REFERENCES pole (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE userm_motivation ADD CONSTRAINT FK_D222E29BD7759491 FOREIGN KEY (userm_id) REFERENCES userm (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE userm_motivation ADD CONSTRAINT FK_D222E29B8EDBCD4E FOREIGN KEY (motivation_id) REFERENCES motivation (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE recette DROP FOREIGN KEY `FK_49BB6390A76ED395`');
        $this->addSql('DROP INDEX IDX_49BB6390A76ED395 ON recette');
        $this->addSql('ALTER TABLE recette DROP user_id');
        $this->addSql('ALTER TABLE user ADD participation_dispo_id INT NOT NULL');
        $this->addSql('ALTER TABLE user ADD CONSTRAINT FK_8D93D6494DA4905E FOREIGN KEY (participation_dispo_id) REFERENCES participation_dispo (id)');
        $this->addSql('CREATE INDEX IDX_8D93D6494DA4905E ON user (participation_dispo_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE userm DROP FOREIGN KEY FK_EE8C988A7A45358C');
        $this->addSql('ALTER TABLE userm_pole DROP FOREIGN KEY FK_B356F04D7759491');
        $this->addSql('ALTER TABLE userm_pole DROP FOREIGN KEY FK_B356F04419C3385');
        $this->addSql('ALTER TABLE userm_motivation DROP FOREIGN KEY FK_D222E29BD7759491');
        $this->addSql('ALTER TABLE userm_motivation DROP FOREIGN KEY FK_D222E29B8EDBCD4E');
        $this->addSql('DROP TABLE userm');
        $this->addSql('DROP TABLE userm_pole');
        $this->addSql('DROP TABLE userm_motivation');
        $this->addSql('ALTER TABLE recette ADD user_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE recette ADD CONSTRAINT `FK_49BB6390A76ED395` FOREIGN KEY (user_id) REFERENCES user (id) ON UPDATE NO ACTION ON DELETE NO ACTION');
        $this->addSql('CREATE INDEX IDX_49BB6390A76ED395 ON recette (user_id)');
        $this->addSql('ALTER TABLE user DROP FOREIGN KEY FK_8D93D6494DA4905E');
        $this->addSql('DROP INDEX IDX_8D93D6494DA4905E ON user');
        $this->addSql('ALTER TABLE user DROP participation_dispo_id');
    }
}
