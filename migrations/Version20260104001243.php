<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20260104001243 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE media (id INT AUTO_INCREMENT NOT NULL, nom_fichier VARCHAR(255) NOT NULL, description VARCHAR(255) NOT NULL, type VARCHAR(50) NOT NULL, role VARCHAR(50) NOT NULL, categorie VARCHAR(100) DEFAULT NULL, recette_id INT DEFAULT NULL, produit_id INT DEFAULT NULL, producteurice_id INT DEFAULT NULL, ressource_id INT DEFAULT NULL, INDEX IDX_6A2CA10C89312FE9 (recette_id), INDEX IDX_6A2CA10CF347EFB (produit_id), INDEX IDX_6A2CA10CEE5BE958 (producteurice_id), INDEX IDX_6A2CA10CFC6CD52A (ressource_id), PRIMARY KEY (id)) DEFAULT CHARACTER SET utf8mb4');
        $this->addSql('ALTER TABLE media ADD CONSTRAINT FK_6A2CA10C89312FE9 FOREIGN KEY (recette_id) REFERENCES recette (id)');
        $this->addSql('ALTER TABLE media ADD CONSTRAINT FK_6A2CA10CF347EFB FOREIGN KEY (produit_id) REFERENCES produit (id)');
        $this->addSql('ALTER TABLE media ADD CONSTRAINT FK_6A2CA10CEE5BE958 FOREIGN KEY (producteurice_id) REFERENCES producteurice (id)');
        $this->addSql('ALTER TABLE media ADD CONSTRAINT FK_6A2CA10CFC6CD52A FOREIGN KEY (ressource_id) REFERENCES ressource (id)');
        $this->addSql('ALTER TABLE recette DROP photo');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE media DROP FOREIGN KEY FK_6A2CA10C89312FE9');
        $this->addSql('ALTER TABLE media DROP FOREIGN KEY FK_6A2CA10CF347EFB');
        $this->addSql('ALTER TABLE media DROP FOREIGN KEY FK_6A2CA10CEE5BE958');
        $this->addSql('ALTER TABLE media DROP FOREIGN KEY FK_6A2CA10CFC6CD52A');
        $this->addSql('DROP TABLE media');
        $this->addSql('ALTER TABLE recette ADD photo VARCHAR(255) NOT NULL');
    }
}
