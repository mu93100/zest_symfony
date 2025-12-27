<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20251226201315 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE referent (id INT AUTO_INCREMENT NOT NULL, date_denregistrement_referent DATE NOT NULL, groupe_id INT NOT NULL, user_id INT NOT NULL, saison_id INT NOT NULL, INDEX IDX_FE9AAC6C7A45358C (groupe_id), INDEX IDX_FE9AAC6CA76ED395 (user_id), INDEX IDX_FE9AAC6CF965414C (saison_id), PRIMARY KEY (id)) DEFAULT CHARACTER SET utf8mb4');
        $this->addSql('ALTER TABLE referent ADD CONSTRAINT FK_FE9AAC6C7A45358C FOREIGN KEY (groupe_id) REFERENCES groupe (id)');
        $this->addSql('ALTER TABLE referent ADD CONSTRAINT FK_FE9AAC6CA76ED395 FOREIGN KEY (user_id) REFERENCES user (id)');
        $this->addSql('ALTER TABLE referent ADD CONSTRAINT FK_FE9AAC6CF965414C FOREIGN KEY (saison_id) REFERENCES saison (id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE referent DROP FOREIGN KEY FK_FE9AAC6C7A45358C');
        $this->addSql('ALTER TABLE referent DROP FOREIGN KEY FK_FE9AAC6CA76ED395');
        $this->addSql('ALTER TABLE referent DROP FOREIGN KEY FK_FE9AAC6CF965414C');
        $this->addSql('DROP TABLE referent');
    }
}
