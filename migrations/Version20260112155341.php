<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20260112155341 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE media DROP FOREIGN KEY `FK_6A2CA10C89312FE9`');
        $this->addSql('ALTER TABLE media DROP FOREIGN KEY `FK_6A2CA10CF347EFB`');
        $this->addSql('ALTER TABLE media DROP FOREIGN KEY `FK_6A2CA10CFC6CD52A`');
        $this->addSql('DROP INDEX IDX_6A2CA10C89312FE9 ON media');
        $this->addSql('DROP INDEX IDX_6A2CA10CF347EFB ON media');
        $this->addSql('DROP INDEX IDX_6A2CA10CFC6CD52A ON media');
        $this->addSql('ALTER TABLE media DROP description, DROP page, DROP recette_id, DROP produit_id, DROP ressource_id');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE media ADD description VARCHAR(255) DEFAULT NULL, ADD page VARCHAR(50) DEFAULT NULL, ADD recette_id INT DEFAULT NULL, ADD produit_id INT DEFAULT NULL, ADD ressource_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE media ADD CONSTRAINT `FK_6A2CA10C89312FE9` FOREIGN KEY (recette_id) REFERENCES recette (id) ON UPDATE NO ACTION ON DELETE CASCADE');
        $this->addSql('ALTER TABLE media ADD CONSTRAINT `FK_6A2CA10CF347EFB` FOREIGN KEY (produit_id) REFERENCES produit (id) ON UPDATE NO ACTION ON DELETE CASCADE');
        $this->addSql('ALTER TABLE media ADD CONSTRAINT `FK_6A2CA10CFC6CD52A` FOREIGN KEY (ressource_id) REFERENCES ressource (id) ON UPDATE NO ACTION ON DELETE CASCADE');
        $this->addSql('CREATE INDEX IDX_6A2CA10C89312FE9 ON media (recette_id)');
        $this->addSql('CREATE INDEX IDX_6A2CA10CF347EFB ON media (produit_id)');
        $this->addSql('CREATE INDEX IDX_6A2CA10CFC6CD52A ON media (ressource_id)');
    }
}
