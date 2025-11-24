<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20251124150541 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE user_pole (user_id INT NOT NULL, pole_id INT NOT NULL, INDEX IDX_87E10E28A76ED395 (user_id), INDEX IDX_87E10E28419C3385 (pole_id), PRIMARY KEY (user_id, pole_id)) DEFAULT CHARACTER SET utf8mb4');
        $this->addSql('CREATE TABLE user_motivation (user_id INT NOT NULL, motivation_id INT NOT NULL, INDEX IDX_4707B501A76ED395 (user_id), INDEX IDX_4707B5018EDBCD4E (motivation_id), PRIMARY KEY (user_id, motivation_id)) DEFAULT CHARACTER SET utf8mb4');
        $this->addSql('ALTER TABLE user_pole ADD CONSTRAINT FK_87E10E28A76ED395 FOREIGN KEY (user_id) REFERENCES user (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE user_pole ADD CONSTRAINT FK_87E10E28419C3385 FOREIGN KEY (pole_id) REFERENCES pole (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE user_motivation ADD CONSTRAINT FK_4707B501A76ED395 FOREIGN KEY (user_id) REFERENCES user (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE user_motivation ADD CONSTRAINT FK_4707B5018EDBCD4E FOREIGN KEY (motivation_id) REFERENCES motivation (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE recette ADD user_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE recette ADD CONSTRAINT FK_49BB6390A76ED395 FOREIGN KEY (user_id) REFERENCES user (id)');
        $this->addSql('CREATE INDEX IDX_49BB6390A76ED395 ON recette (user_id)');
        $this->addSql('ALTER TABLE ressource ADD auteurice_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE ressource ADD CONSTRAINT FK_939F454455D7EF5A FOREIGN KEY (auteurice_id) REFERENCES user (id)');
        $this->addSql('CREATE INDEX IDX_939F454455D7EF5A ON ressource (auteurice_id)');
        $this->addSql('ALTER TABLE user ADD groupe_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE user ADD CONSTRAINT FK_8D93D6497A45358C FOREIGN KEY (groupe_id) REFERENCES groupe (id)');
        $this->addSql('CREATE INDEX IDX_8D93D6497A45358C ON user (groupe_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE user_pole DROP FOREIGN KEY FK_87E10E28A76ED395');
        $this->addSql('ALTER TABLE user_pole DROP FOREIGN KEY FK_87E10E28419C3385');
        $this->addSql('ALTER TABLE user_motivation DROP FOREIGN KEY FK_4707B501A76ED395');
        $this->addSql('ALTER TABLE user_motivation DROP FOREIGN KEY FK_4707B5018EDBCD4E');
        $this->addSql('DROP TABLE user_pole');
        $this->addSql('DROP TABLE user_motivation');
        $this->addSql('ALTER TABLE recette DROP FOREIGN KEY FK_49BB6390A76ED395');
        $this->addSql('DROP INDEX IDX_49BB6390A76ED395 ON recette');
        $this->addSql('ALTER TABLE recette DROP user_id');
        $this->addSql('ALTER TABLE ressource DROP FOREIGN KEY FK_939F454455D7EF5A');
        $this->addSql('DROP INDEX IDX_939F454455D7EF5A ON ressource');
        $this->addSql('ALTER TABLE ressource DROP auteurice_id');
        $this->addSql('ALTER TABLE user DROP FOREIGN KEY FK_8D93D6497A45358C');
        $this->addSql('DROP INDEX IDX_8D93D6497A45358C ON user');
        $this->addSql('ALTER TABLE user DROP groupe_id');
    }
}
