<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20251209092917 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE adhesion ADD CONSTRAINT FK_C50CA65ABBAAE0A4 FOREIGN KEY (montant_adhesion_id) REFERENCES montant_adhesion (id)');
        $this->addSql('ALTER TABLE adhesion ADD CONSTRAINT FK_C50CA65AF965414C FOREIGN KEY (saison_id) REFERENCES saison (id)');
        $this->addSql('CREATE INDEX IDX_C50CA65ABBAAE0A4 ON adhesion (montant_adhesion_id)');
        $this->addSql('CREATE INDEX IDX_C50CA65AF965414C ON adhesion (saison_id)');
        $this->addSql('ALTER TABLE adhesion RENAME INDEX fk_c50ca65aa76ed395 TO IDX_C50CA65AA76ED395');
        $this->addSql('ALTER TABLE adhesion RENAME INDEX fk_c50ca65a7a45358c TO IDX_C50CA65A7A45358C');
        $this->addSql('ALTER TABLE adhesion_motivation ADD CONSTRAINT FK_690EC6E4F68139D7 FOREIGN KEY (adhesion_id) REFERENCES adhesion (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE adhesion_motivation ADD CONSTRAINT FK_690EC6E48EDBCD4E FOREIGN KEY (motivation_id) REFERENCES motivation (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE adhesion_participation_dispo ADD CONSTRAINT FK_9764DC94F68139D7 FOREIGN KEY (adhesion_id) REFERENCES adhesion (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE adhesion_participation_dispo ADD CONSTRAINT FK_9764DC944DA4905E FOREIGN KEY (participation_dispo_id) REFERENCES participation_dispo (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE adhesion_pole ADD CONSTRAINT FK_5717926F68139D7 FOREIGN KEY (adhesion_id) REFERENCES adhesion (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE adhesion_pole ADD CONSTRAINT FK_5717926419C3385 FOREIGN KEY (pole_id) REFERENCES pole (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE groupe DROP referent_nom, DROP referent_email, DROP referent_telephone');
        $this->addSql('ALTER TABLE producteurice_produit ADD CONSTRAINT FK_FCA015B5EE5BE958 FOREIGN KEY (producteurice_id) REFERENCES producteurice (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE producteurice_produit ADD CONSTRAINT FK_FCA015B5F347EFB FOREIGN KEY (produit_id) REFERENCES produit (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE reset_password_request ADD CONSTRAINT FK_7CE748AA76ED395 FOREIGN KEY (user_id) REFERENCES user (id)');
        $this->addSql('ALTER TABLE user DROP FOREIGN KEY `FK_8D93D6494DA4905E`');
        $this->addSql('ALTER TABLE user DROP FOREIGN KEY `FK_8D93D649F68139D7`');
        $this->addSql('DROP INDEX IDX_8D93D6494DA4905E ON user');
        $this->addSql('DROP INDEX IDX_8D93D649F68139D7 ON user');
        $this->addSql('ALTER TABLE user DROP motivations_attentes, DROP adhesion_id, DROP participation_dispo_id');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE adhesion DROP FOREIGN KEY FK_C50CA65ABBAAE0A4');
        $this->addSql('ALTER TABLE adhesion DROP FOREIGN KEY FK_C50CA65AF965414C');
        $this->addSql('DROP INDEX IDX_C50CA65ABBAAE0A4 ON adhesion');
        $this->addSql('DROP INDEX IDX_C50CA65AF965414C ON adhesion');
        $this->addSql('ALTER TABLE adhesion RENAME INDEX idx_c50ca65aa76ed395 TO FK_C50CA65AA76ED395');
        $this->addSql('ALTER TABLE adhesion RENAME INDEX idx_c50ca65a7a45358c TO FK_C50CA65A7A45358C');
        $this->addSql('ALTER TABLE adhesion_motivation DROP FOREIGN KEY FK_690EC6E4F68139D7');
        $this->addSql('ALTER TABLE adhesion_motivation DROP FOREIGN KEY FK_690EC6E48EDBCD4E');
        $this->addSql('ALTER TABLE adhesion_participation_dispo DROP FOREIGN KEY FK_9764DC94F68139D7');
        $this->addSql('ALTER TABLE adhesion_participation_dispo DROP FOREIGN KEY FK_9764DC944DA4905E');
        $this->addSql('ALTER TABLE adhesion_pole DROP FOREIGN KEY FK_5717926F68139D7');
        $this->addSql('ALTER TABLE adhesion_pole DROP FOREIGN KEY FK_5717926419C3385');
        $this->addSql('ALTER TABLE groupe ADD referent_nom VARCHAR(100) DEFAULT NULL, ADD referent_email VARCHAR(100) DEFAULT NULL, ADD referent_telephone VARCHAR(20) DEFAULT NULL');
        $this->addSql('ALTER TABLE producteurice_produit DROP FOREIGN KEY FK_FCA015B5EE5BE958');
        $this->addSql('ALTER TABLE producteurice_produit DROP FOREIGN KEY FK_FCA015B5F347EFB');
        $this->addSql('ALTER TABLE reset_password_request DROP FOREIGN KEY FK_7CE748AA76ED395');
        $this->addSql('ALTER TABLE user ADD motivations_attentes LONGTEXT DEFAULT NULL, ADD adhesion_id INT NOT NULL, ADD participation_dispo_id INT NOT NULL');
        $this->addSql('ALTER TABLE user ADD CONSTRAINT `FK_8D93D6494DA4905E` FOREIGN KEY (participation_dispo_id) REFERENCES participation_dispo (id) ON UPDATE NO ACTION ON DELETE NO ACTION');
        $this->addSql('ALTER TABLE user ADD CONSTRAINT `FK_8D93D649F68139D7` FOREIGN KEY (adhesion_id) REFERENCES adhesion (id) ON UPDATE NO ACTION ON DELETE NO ACTION');
        $this->addSql('CREATE INDEX IDX_8D93D6494DA4905E ON user (participation_dispo_id)');
        $this->addSql('CREATE INDEX IDX_8D93D649F68139D7 ON user (adhesion_id)');
    }
}
