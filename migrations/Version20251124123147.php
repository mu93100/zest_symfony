<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20251124123147 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE ressource (id INT AUTO_INCREMENT NOT NULL, date DATETIME NOT NULL, titre VARCHAR(300) NOT NULL, sous_titre VARCHAR(300) DEFAULT NULL, ressource_texte LONGTEXT NOT NULL, photo VARCHAR(255) NOT NULL, photos_supp VARCHAR(255) NOT NULL, PRIMARY KEY (id)) DEFAULT CHARACTER SET utf8mb4');
        $this->addSql('ALTER TABLE producteur CHANGE site site VARCHAR(255) NOT NULL, CHANGE lien_produits lien_produits VARCHAR(255) DEFAULT NULL');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('DROP TABLE ressource');
        $this->addSql('ALTER TABLE producteur CHANGE site site VARCHAR(255) DEFAULT NULL, CHANGE lien_produits lien_produits VARCHAR(255) NOT NULL');
    }
}
