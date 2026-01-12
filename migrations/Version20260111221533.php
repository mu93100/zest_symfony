<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20260111221533 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE media DROP FOREIGN KEY `FK_6A2CA10CEE5BE958`');
        $this->addSql('ALTER TABLE media DROP FOREIGN KEY `FK_6A2CA10CF347EFB`');
        $this->addSql('ALTER TABLE media DROP FOREIGN KEY `FK_6A2CA10CFC6CD52A`');
        $this->addSql('ALTER TABLE media CHANGE description description VARCHAR(255) DEFAULT NULL, CHANGE page page VARCHAR(50) DEFAULT NULL, CHANGE role role VARCHAR(50) DEFAULT NULL');
        $this->addSql('ALTER TABLE media ADD CONSTRAINT FK_6A2CA10CEE5BE958 FOREIGN KEY (producteurice_id) REFERENCES producteurice (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE media ADD CONSTRAINT FK_6A2CA10CF347EFB FOREIGN KEY (produit_id) REFERENCES produit (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE media ADD CONSTRAINT FK_6A2CA10CFC6CD52A FOREIGN KEY (ressource_id) REFERENCES ressource (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE producteurice DROP logo');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE media DROP FOREIGN KEY FK_6A2CA10CF347EFB');
        $this->addSql('ALTER TABLE media DROP FOREIGN KEY FK_6A2CA10CEE5BE958');
        $this->addSql('ALTER TABLE media DROP FOREIGN KEY FK_6A2CA10CFC6CD52A');
        $this->addSql('ALTER TABLE media CHANGE description description VARCHAR(255) NOT NULL, CHANGE page page VARCHAR(50) NOT NULL, CHANGE role role VARCHAR(50) NOT NULL');
        $this->addSql('ALTER TABLE media ADD CONSTRAINT `FK_6A2CA10CF347EFB` FOREIGN KEY (produit_id) REFERENCES produit (id) ON UPDATE NO ACTION ON DELETE NO ACTION');
        $this->addSql('ALTER TABLE media ADD CONSTRAINT `FK_6A2CA10CEE5BE958` FOREIGN KEY (producteurice_id) REFERENCES producteurice (id) ON UPDATE NO ACTION ON DELETE NO ACTION');
        $this->addSql('ALTER TABLE media ADD CONSTRAINT `FK_6A2CA10CFC6CD52A` FOREIGN KEY (ressource_id) REFERENCES ressource (id) ON UPDATE NO ACTION ON DELETE NO ACTION');
        $this->addSql('ALTER TABLE producteurice ADD logo VARCHAR(255) NOT NULL');
    }
}
