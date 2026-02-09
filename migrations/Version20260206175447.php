<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20260206175447 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE groupe_referent_saison (id INT AUTO_INCREMENT NOT NULL, saison_id INT NOT NULL, groupe_id INT NOT NULL, referent_id INT NOT NULL, INDEX IDX_6F90F1FBF965414C (saison_id), INDEX IDX_6F90F1FB7A45358C (groupe_id), INDEX IDX_6F90F1FB35E47E35 (referent_id), PRIMARY KEY (id)) DEFAULT CHARACTER SET utf8mb4');
        $this->addSql('ALTER TABLE groupe_referent_saison ADD CONSTRAINT FK_6F90F1FBF965414C FOREIGN KEY (saison_id) REFERENCES saison (id)');
        $this->addSql('ALTER TABLE groupe_referent_saison ADD CONSTRAINT FK_6F90F1FB7A45358C FOREIGN KEY (groupe_id) REFERENCES groupe (id)');
        $this->addSql('ALTER TABLE groupe_referent_saison ADD CONSTRAINT FK_6F90F1FB35E47E35 FOREIGN KEY (referent_id) REFERENCES user (id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE groupe_referent_saison DROP FOREIGN KEY FK_6F90F1FBF965414C');
        $this->addSql('ALTER TABLE groupe_referent_saison DROP FOREIGN KEY FK_6F90F1FB7A45358C');
        $this->addSql('ALTER TABLE groupe_referent_saison DROP FOREIGN KEY FK_6F90F1FB35E47E35');
        $this->addSql('DROP TABLE groupe_referent_saison');
    }
}
