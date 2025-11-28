<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20251128141919 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE photos ADD photos_supp_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE photos ADD CONSTRAINT FK_876E0D9935F5A8B FOREIGN KEY (photos_supp_id) REFERENCES ressource (id)');
        $this->addSql('CREATE INDEX IDX_876E0D9935F5A8B ON photos (photos_supp_id)');
        $this->addSql('ALTER TABLE producteurice_produit ADD CONSTRAINT FK_FCA015B5EE5BE958 FOREIGN KEY (producteurice_id) REFERENCES producteurice (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE producteurice_produit ADD CONSTRAINT FK_FCA015B5F347EFB FOREIGN KEY (produit_id) REFERENCES produit (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE reset_password_request ADD CONSTRAINT FK_7CE748AA76ED395 FOREIGN KEY (user_id) REFERENCES user (id)');
        $this->addSql('ALTER TABLE ressource ADD photo_principale_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE ressource ADD CONSTRAINT FK_939F454451C718BE FOREIGN KEY (photo_principale_id) REFERENCES photos (id)');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_939F454451C718BE ON ressource (photo_principale_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE photos DROP FOREIGN KEY FK_876E0D9935F5A8B');
        $this->addSql('DROP INDEX IDX_876E0D9935F5A8B ON photos');
        $this->addSql('ALTER TABLE photos DROP photos_supp_id');
        $this->addSql('ALTER TABLE producteurice_produit DROP FOREIGN KEY FK_FCA015B5EE5BE958');
        $this->addSql('ALTER TABLE producteurice_produit DROP FOREIGN KEY FK_FCA015B5F347EFB');
        $this->addSql('ALTER TABLE reset_password_request DROP FOREIGN KEY FK_7CE748AA76ED395');
        $this->addSql('ALTER TABLE ressource DROP FOREIGN KEY FK_939F454451C718BE');
        $this->addSql('DROP INDEX UNIQ_939F454451C718BE ON ressource');
        $this->addSql('ALTER TABLE ressource DROP photo_principale_id');
    }
}
