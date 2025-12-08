<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20251205155733 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE adhesion CHANGE libelle_adhesion libelle VARCHAR(100) NOT NULL');
        $this->addSql('ALTER TABLE groupe ADD referent_nom VARCHAR(100) DEFAULT NULL, ADD referent_email VARCHAR(100) DEFAULT NULL, ADD referent_telephone VARCHAR(20) DEFAULT NULL, CHANGE is_groupe_open is_open TINYINT(1) NOT NULL');
        $this->addSql('ALTER TABLE producteurice_produit ADD CONSTRAINT FK_FCA015B5EE5BE958 FOREIGN KEY (producteurice_id) REFERENCES producteurice (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE producteurice_produit ADD CONSTRAINT FK_FCA015B5F347EFB FOREIGN KEY (produit_id) REFERENCES produit (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE reset_password_request ADD CONSTRAINT FK_7CE748AA76ED395 FOREIGN KEY (user_id) REFERENCES user (id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE adhesion CHANGE libelle libelle_adhesion VARCHAR(100) NOT NULL');
        $this->addSql('ALTER TABLE groupe DROP referent_nom, DROP referent_email, DROP referent_telephone, CHANGE is_open is_groupe_open TINYINT(1) NOT NULL');
        $this->addSql('ALTER TABLE producteurice_produit DROP FOREIGN KEY FK_FCA015B5EE5BE958');
        $this->addSql('ALTER TABLE producteurice_produit DROP FOREIGN KEY FK_FCA015B5F347EFB');
        $this->addSql('ALTER TABLE reset_password_request DROP FOREIGN KEY FK_7CE748AA76ED395');
    }
}
