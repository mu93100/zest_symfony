<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20251210150716 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE ressource DROP is_publication, CHANGE titre titre VARCHAR(150) NOT NULL, CHANGE sous_titre sous_titre VARCHAR(200) NOT NULL, CHANGE date date_publication DATETIME NOT NULL');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE ressource ADD is_publication TINYINT(1) NOT NULL, CHANGE titre titre VARCHAR(300) NOT NULL, CHANGE sous_titre sous_titre VARCHAR(300) DEFAULT NULL, CHANGE date_publication date DATETIME NOT NULL');
    }
}
