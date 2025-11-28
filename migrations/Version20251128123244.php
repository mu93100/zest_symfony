<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20251128123244 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE producteurice_produit ADD CONSTRAINT FK_FCA015B5EE5BE958 FOREIGN KEY (producteurice_id) REFERENCES producteurice (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE producteurice_produit ADD CONSTRAINT FK_FCA015B5F347EFB FOREIGN KEY (produit_id) REFERENCES produit (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE recette DROP FOREIGN KEY `FK_49BB639055D7EF5A`');
        $this->addSql('DROP INDEX IDX_49BB639055D7EF5A ON recette');
        $this->addSql('ALTER TABLE recette DROP auteurice_id');
        $this->addSql('ALTER TABLE reset_password_request ADD CONSTRAINT FK_7CE748AA76ED395 FOREIGN KEY (user_id) REFERENCES user (id)');
        $this->addSql('ALTER TABLE ressource DROP FOREIGN KEY `FK_939F454455D7EF5A`');
        $this->addSql('DROP INDEX IDX_939F454455D7EF5A ON ressource');
        $this->addSql('ALTER TABLE ressource ADD user_id INT DEFAULT NULL, DROP auteurice_id');
        $this->addSql('ALTER TABLE ressource ADD CONSTRAINT FK_939F4544A76ED395 FOREIGN KEY (user_id) REFERENCES user (id)');
        $this->addSql('CREATE INDEX IDX_939F4544A76ED395 ON ressource (user_id)');
        $this->addSql('ALTER TABLE user ADD is_verified TINYINT(1) NOT NULL');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE producteurice_produit DROP FOREIGN KEY FK_FCA015B5EE5BE958');
        $this->addSql('ALTER TABLE producteurice_produit DROP FOREIGN KEY FK_FCA015B5F347EFB');
        $this->addSql('ALTER TABLE recette ADD auteurice_id INT NOT NULL');
        $this->addSql('ALTER TABLE recette ADD CONSTRAINT `FK_49BB639055D7EF5A` FOREIGN KEY (auteurice_id) REFERENCES user (id) ON UPDATE NO ACTION ON DELETE NO ACTION');
        $this->addSql('CREATE INDEX IDX_49BB639055D7EF5A ON recette (auteurice_id)');
        $this->addSql('ALTER TABLE reset_password_request DROP FOREIGN KEY FK_7CE748AA76ED395');
        $this->addSql('ALTER TABLE ressource DROP FOREIGN KEY FK_939F4544A76ED395');
        $this->addSql('DROP INDEX IDX_939F4544A76ED395 ON ressource');
        $this->addSql('ALTER TABLE ressource ADD auteurice_id INT NOT NULL, DROP user_id');
        $this->addSql('ALTER TABLE ressource ADD CONSTRAINT `FK_939F454455D7EF5A` FOREIGN KEY (auteurice_id) REFERENCES user (id) ON UPDATE NO ACTION ON DELETE NO ACTION');
        $this->addSql('CREATE INDEX IDX_939F454455D7EF5A ON ressource (auteurice_id)');
        $this->addSql('ALTER TABLE user DROP is_verified');
    }
}
