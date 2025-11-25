<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20251125100842 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE producteur_produit (producteur_id INT NOT NULL, produit_id INT NOT NULL, INDEX IDX_4CD25379AB9BB300 (producteur_id), INDEX IDX_4CD25379F347EFB (produit_id), PRIMARY KEY (producteur_id, produit_id)) DEFAULT CHARACTER SET utf8mb4');
        $this->addSql('CREATE TABLE recette_produit (recette_id INT NOT NULL, produit_id INT NOT NULL, INDEX IDX_EDDD365D89312FE9 (recette_id), INDEX IDX_EDDD365DF347EFB (produit_id), PRIMARY KEY (recette_id, produit_id)) DEFAULT CHARACTER SET utf8mb4');
        $this->addSql('ALTER TABLE producteur_produit ADD CONSTRAINT FK_4CD25379AB9BB300 FOREIGN KEY (producteur_id) REFERENCES producteur (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE producteur_produit ADD CONSTRAINT FK_4CD25379F347EFB FOREIGN KEY (produit_id) REFERENCES produit (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE recette_produit ADD CONSTRAINT FK_EDDD365D89312FE9 FOREIGN KEY (recette_id) REFERENCES recette (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE recette_produit ADD CONSTRAINT FK_EDDD365DF347EFB FOREIGN KEY (produit_id) REFERENCES produit (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE recette ADD CONSTRAINT FK_49BB6390A76ED395 FOREIGN KEY (user_id) REFERENCES user (id)');
        $this->addSql('ALTER TABLE ressource ADD CONSTRAINT FK_939F454455D7EF5A FOREIGN KEY (auteurice_id) REFERENCES user (id)');
        $this->addSql('ALTER TABLE ressource ADD CONSTRAINT FK_939F4544BCF5E72D FOREIGN KEY (categorie_id) REFERENCES categorie (id)');
        $this->addSql('ALTER TABLE ressource ADD CONSTRAINT FK_939F4544419C3385 FOREIGN KEY (pole_id) REFERENCES pole (id)');
        $this->addSql('ALTER TABLE ressource_photos ADD CONSTRAINT FK_E2373364FC6CD52A FOREIGN KEY (ressource_id) REFERENCES ressource (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE ressource_photos ADD CONSTRAINT FK_E2373364301EC62 FOREIGN KEY (photos_id) REFERENCES photos (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE user ADD CONSTRAINT FK_8D93D6497A45358C FOREIGN KEY (groupe_id) REFERENCES groupe (id)');
        $this->addSql('ALTER TABLE user_pole ADD CONSTRAINT FK_87E10E28A76ED395 FOREIGN KEY (user_id) REFERENCES user (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE user_pole ADD CONSTRAINT FK_87E10E28419C3385 FOREIGN KEY (pole_id) REFERENCES pole (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE user_motivation ADD CONSTRAINT FK_4707B501A76ED395 FOREIGN KEY (user_id) REFERENCES user (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE user_motivation ADD CONSTRAINT FK_4707B5018EDBCD4E FOREIGN KEY (motivation_id) REFERENCES motivation (id) ON DELETE CASCADE');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE producteur_produit DROP FOREIGN KEY FK_4CD25379AB9BB300');
        $this->addSql('ALTER TABLE producteur_produit DROP FOREIGN KEY FK_4CD25379F347EFB');
        $this->addSql('ALTER TABLE recette_produit DROP FOREIGN KEY FK_EDDD365D89312FE9');
        $this->addSql('ALTER TABLE recette_produit DROP FOREIGN KEY FK_EDDD365DF347EFB');
        $this->addSql('DROP TABLE producteur_produit');
        $this->addSql('DROP TABLE recette_produit');
        $this->addSql('ALTER TABLE recette DROP FOREIGN KEY FK_49BB6390A76ED395');
        $this->addSql('ALTER TABLE ressource DROP FOREIGN KEY FK_939F454455D7EF5A');
        $this->addSql('ALTER TABLE ressource DROP FOREIGN KEY FK_939F4544BCF5E72D');
        $this->addSql('ALTER TABLE ressource DROP FOREIGN KEY FK_939F4544419C3385');
        $this->addSql('ALTER TABLE ressource_photos DROP FOREIGN KEY FK_E2373364FC6CD52A');
        $this->addSql('ALTER TABLE ressource_photos DROP FOREIGN KEY FK_E2373364301EC62');
        $this->addSql('ALTER TABLE user DROP FOREIGN KEY FK_8D93D6497A45358C');
        $this->addSql('ALTER TABLE user_motivation DROP FOREIGN KEY FK_4707B501A76ED395');
        $this->addSql('ALTER TABLE user_motivation DROP FOREIGN KEY FK_4707B5018EDBCD4E');
        $this->addSql('ALTER TABLE user_pole DROP FOREIGN KEY FK_87E10E28A76ED395');
        $this->addSql('ALTER TABLE user_pole DROP FOREIGN KEY FK_87E10E28419C3385');
    }
}
