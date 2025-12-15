<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20251215113354 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE adhesion_dispo ADD CONSTRAINT FK_4DC30B07F68139D7 FOREIGN KEY (adhesion_id) REFERENCES adhesion (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE adhesion_dispo ADD CONSTRAINT FK_4DC30B07A18C1CC9 FOREIGN KEY (dispo_id) REFERENCES dispo (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE groupe ADD adresse_distribution VARCHAR(255) DEFAULT NULL');
        $this->addSql('ALTER TABLE user ADD is_referent TINYINT(1) NOT NULL');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE adhesion_dispo DROP FOREIGN KEY FK_4DC30B07F68139D7');
        $this->addSql('ALTER TABLE adhesion_dispo DROP FOREIGN KEY FK_4DC30B07A18C1CC9');
        $this->addSql('ALTER TABLE groupe DROP adresse_distribution');
        $this->addSql('ALTER TABLE user DROP is_referent');
    }
}
