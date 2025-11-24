<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20251124151402 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE ressource_photos (ressource_id INT NOT NULL, photos_id INT NOT NULL, INDEX IDX_E2373364FC6CD52A (ressource_id), INDEX IDX_E2373364301EC62 (photos_id), PRIMARY KEY (ressource_id, photos_id)) DEFAULT CHARACTER SET utf8mb4');
        $this->addSql('ALTER TABLE ressource_photos ADD CONSTRAINT FK_E2373364FC6CD52A FOREIGN KEY (ressource_id) REFERENCES ressource (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE ressource_photos ADD CONSTRAINT FK_E2373364301EC62 FOREIGN KEY (photos_id) REFERENCES photos (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE recette ADD CONSTRAINT FK_49BB6390A76ED395 FOREIGN KEY (user_id) REFERENCES user (id)');
        $this->addSql('ALTER TABLE ressource ADD categorie_id INT NOT NULL, ADD pole_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE ressource ADD CONSTRAINT FK_939F454455D7EF5A FOREIGN KEY (auteurice_id) REFERENCES user (id)');
        $this->addSql('ALTER TABLE ressource ADD CONSTRAINT FK_939F4544BCF5E72D FOREIGN KEY (categorie_id) REFERENCES categorie (id)');
        $this->addSql('ALTER TABLE ressource ADD CONSTRAINT FK_939F4544419C3385 FOREIGN KEY (pole_id) REFERENCES pole (id)');
        $this->addSql('CREATE INDEX IDX_939F4544BCF5E72D ON ressource (categorie_id)');
        $this->addSql('CREATE INDEX IDX_939F4544419C3385 ON ressource (pole_id)');
        $this->addSql('ALTER TABLE user ADD CONSTRAINT FK_8D93D6497A45358C FOREIGN KEY (groupe_id) REFERENCES groupe (id)');
        $this->addSql('ALTER TABLE user_pole ADD CONSTRAINT FK_87E10E28A76ED395 FOREIGN KEY (user_id) REFERENCES user (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE user_pole ADD CONSTRAINT FK_87E10E28419C3385 FOREIGN KEY (pole_id) REFERENCES pole (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE user_motivation ADD CONSTRAINT FK_4707B501A76ED395 FOREIGN KEY (user_id) REFERENCES user (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE user_motivation ADD CONSTRAINT FK_4707B5018EDBCD4E FOREIGN KEY (motivation_id) REFERENCES motivation (id) ON DELETE CASCADE');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE ressource_photos DROP FOREIGN KEY FK_E2373364FC6CD52A');
        $this->addSql('ALTER TABLE ressource_photos DROP FOREIGN KEY FK_E2373364301EC62');
        $this->addSql('DROP TABLE ressource_photos');
        $this->addSql('ALTER TABLE recette DROP FOREIGN KEY FK_49BB6390A76ED395');
        $this->addSql('ALTER TABLE ressource DROP FOREIGN KEY FK_939F454455D7EF5A');
        $this->addSql('ALTER TABLE ressource DROP FOREIGN KEY FK_939F4544BCF5E72D');
        $this->addSql('ALTER TABLE ressource DROP FOREIGN KEY FK_939F4544419C3385');
        $this->addSql('DROP INDEX IDX_939F4544BCF5E72D ON ressource');
        $this->addSql('DROP INDEX IDX_939F4544419C3385 ON ressource');
        $this->addSql('ALTER TABLE ressource DROP categorie_id, DROP pole_id');
        $this->addSql('ALTER TABLE user DROP FOREIGN KEY FK_8D93D6497A45358C');
        $this->addSql('ALTER TABLE user_motivation DROP FOREIGN KEY FK_4707B501A76ED395');
        $this->addSql('ALTER TABLE user_motivation DROP FOREIGN KEY FK_4707B5018EDBCD4E');
        $this->addSql('ALTER TABLE user_pole DROP FOREIGN KEY FK_87E10E28A76ED395');
        $this->addSql('ALTER TABLE user_pole DROP FOREIGN KEY FK_87E10E28419C3385');
    }
}
