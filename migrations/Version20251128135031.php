<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20251128135031 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE ressource_photos DROP FOREIGN KEY `FK_E2373364301EC62`');
        $this->addSql('ALTER TABLE ressource_photos DROP FOREIGN KEY `FK_E2373364FC6CD52A`');
        $this->addSql('DROP TABLE ressource_photos');
        $this->addSql('ALTER TABLE producteurice_produit ADD CONSTRAINT FK_FCA015B5EE5BE958 FOREIGN KEY (producteurice_id) REFERENCES producteurice (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE producteurice_produit ADD CONSTRAINT FK_FCA015B5F347EFB FOREIGN KEY (produit_id) REFERENCES produit (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE reset_password_request ADD CONSTRAINT FK_7CE748AA76ED395 FOREIGN KEY (user_id) REFERENCES user (id)');
        $this->addSql('ALTER TABLE ressource DROP photo, DROP photos_supp');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE ressource_photos (ressource_id INT NOT NULL, photos_id INT NOT NULL, INDEX IDX_E2373364301EC62 (photos_id), INDEX IDX_E2373364FC6CD52A (ressource_id), PRIMARY KEY (ressource_id, photos_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_0900_ai_ci` ENGINE = InnoDB COMMENT = \'\' ');
        $this->addSql('ALTER TABLE ressource_photos ADD CONSTRAINT `FK_E2373364301EC62` FOREIGN KEY (photos_id) REFERENCES photos (id) ON UPDATE NO ACTION ON DELETE CASCADE');
        $this->addSql('ALTER TABLE ressource_photos ADD CONSTRAINT `FK_E2373364FC6CD52A` FOREIGN KEY (ressource_id) REFERENCES ressource (id) ON UPDATE NO ACTION ON DELETE CASCADE');
        $this->addSql('ALTER TABLE producteurice_produit DROP FOREIGN KEY FK_FCA015B5EE5BE958');
        $this->addSql('ALTER TABLE producteurice_produit DROP FOREIGN KEY FK_FCA015B5F347EFB');
        $this->addSql('ALTER TABLE reset_password_request DROP FOREIGN KEY FK_7CE748AA76ED395');
        $this->addSql('ALTER TABLE ressource ADD photo VARCHAR(255) NOT NULL, ADD photos_supp VARCHAR(255) NOT NULL');
    }
}
