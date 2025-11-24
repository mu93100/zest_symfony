<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20251124124605 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE pole (id INT AUTO_INCREMENT NOT NULL, nom VARCHAR(100) NOT NULL, descriptif LONGTEXT NOT NULL, descriptif_pdf VARCHAR(255) NOT NULL, volume_horaire INT NOT NULL, PRIMARY KEY (id)) DEFAULT CHARACTER SET utf8mb4');
        $this->addSql('ALTER TABLE ressource CHANGE ressource_texte ressource_texte LONGTEXT NOT NULL');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('DROP TABLE pole');
        $this->addSql('ALTER TABLE ressource CHANGE ressource_texte ressource_texte LONGTEXT DEFAULT NULL');
    }
}
