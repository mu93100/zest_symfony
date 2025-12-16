<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20251216143813 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

public function up(Schema $schema): void
{
    // 1. Supprimer temporairement la contrainte de clé étrangère
    $this->addSql('ALTER TABLE user DROP FOREIGN KEY FK_8D93D6497A45358C');
    
    // 2. Ajouter le nouveau champ adresse_distribution dans groupe
    $this->addSql('ALTER TABLE groupe ADD adresse_distribution VARCHAR(255) DEFAULT NULL');
    
    // 3. Recréer la contrainte
    $this->addSql('ALTER TABLE user ADD CONSTRAINT FK_8D93D6497A45358C FOREIGN KEY (groupe_id) REFERENCES groupe (id)');
}

public function down(Schema $schema): void
{
    // Pour annuler la migration si besoin
    $this->addSql('ALTER TABLE user DROP FOREIGN KEY FK_8D93D6497A45358C');
    $this->addSql('ALTER TABLE groupe DROP adresse_distribution');
    $this->addSql('ALTER TABLE user ADD CONSTRAINT FK_8D93D6497A45358C FOREIGN KEY (groupe_id) REFERENCES groupe (id)');
}
}
