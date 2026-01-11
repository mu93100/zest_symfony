<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20260110230341 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE producteurice DROP produits');
        $this->addSql('ALTER TABLE producteurice_produit DROP PRIMARY KEY, ADD PRIMARY KEY (produit_id, producteurice_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE producteurice ADD produits VARCHAR(255) NOT NULL');
        $this->addSql('ALTER TABLE producteurice_produit DROP PRIMARY KEY, ADD PRIMARY KEY (producteurice_id, produit_id)');
    }
}
