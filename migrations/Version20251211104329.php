<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20251211104329 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE adhesion_dispo (adhesion_id INT NOT NULL, dispo_id INT NOT NULL, INDEX IDX_4DC30B07F68139D7 (adhesion_id), INDEX IDX_4DC30B07A18C1CC9 (dispo_id), PRIMARY KEY (adhesion_id, dispo_id)) DEFAULT CHARACTER SET utf8mb4');
        $this->addSql('CREATE TABLE dispo (id INT AUTO_INCREMENT NOT NULL, libelle_dispo VARCHAR(200) NOT NULL, PRIMARY KEY (id)) DEFAULT CHARACTER SET utf8mb4');
        $this->addSql('ALTER TABLE adhesion_dispo ADD CONSTRAINT FK_4DC30B07F68139D7 FOREIGN KEY (adhesion_id) REFERENCES adhesion (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE adhesion_dispo ADD CONSTRAINT FK_4DC30B07A18C1CC9 FOREIGN KEY (dispo_id) REFERENCES dispo (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE adhesion_participation_dispo DROP FOREIGN KEY `FK_9764DC944DA4905E`');
        $this->addSql('ALTER TABLE adhesion_participation_dispo DROP FOREIGN KEY `FK_9764DC94F68139D7`');
        $this->addSql('DROP TABLE adhesion_participation_dispo');
        $this->addSql('DROP TABLE participation_dispo');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE adhesion_participation_dispo (adhesion_id INT NOT NULL, participation_dispo_id INT NOT NULL, INDEX IDX_9764DC944DA4905E (participation_dispo_id), INDEX IDX_9764DC94F68139D7 (adhesion_id), PRIMARY KEY (adhesion_id, participation_dispo_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_0900_ai_ci` ENGINE = InnoDB COMMENT = \'\' ');
        $this->addSql('CREATE TABLE participation_dispo (id INT AUTO_INCREMENT NOT NULL, libelle_dispo VARCHAR(200) CHARACTER SET utf8mb4 NOT NULL COLLATE `utf8mb4_0900_ai_ci`, PRIMARY KEY (id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_0900_ai_ci` ENGINE = InnoDB COMMENT = \'\' ');
        $this->addSql('ALTER TABLE adhesion_participation_dispo ADD CONSTRAINT `FK_9764DC944DA4905E` FOREIGN KEY (participation_dispo_id) REFERENCES participation_dispo (id) ON UPDATE NO ACTION ON DELETE CASCADE');
        $this->addSql('ALTER TABLE adhesion_participation_dispo ADD CONSTRAINT `FK_9764DC94F68139D7` FOREIGN KEY (adhesion_id) REFERENCES adhesion (id) ON UPDATE NO ACTION ON DELETE CASCADE');
        $this->addSql('ALTER TABLE adhesion_dispo DROP FOREIGN KEY FK_4DC30B07F68139D7');
        $this->addSql('ALTER TABLE adhesion_dispo DROP FOREIGN KEY FK_4DC30B07A18C1CC9');
        $this->addSql('DROP TABLE adhesion_dispo');
        $this->addSql('DROP TABLE dispo');
    }
}
