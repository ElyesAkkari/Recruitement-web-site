<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20210401104415 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE categories (id INT AUTO_INCREMENT NOT NULL, nom VARCHAR(250) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE commantaire (id INT AUTO_INCREMENT NOT NULL, iduser_id INT NOT NULL, idformation_id INT NOT NULL, body VARCHAR(255) NOT NULL, created DATETIME NOT NULL, INDEX IDX_93BF4CAF786A81FB (iduser_id), INDEX IDX_93BF4CAF14AF5727 (idformation_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE formateur (id INT AUTO_INCREMENT NOT NULL, nom VARCHAR(250) NOT NULL, prenom VARCHAR(250) NOT NULL, email VARCHAR(250) NOT NULL, numtel INT NOT NULL, image VARCHAR(250) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE formation (id INT AUTO_INCREMENT NOT NULL, nomformateur_id INT NOT NULL, categories_id INT NOT NULL, prix DOUBLE PRECISION NOT NULL, nbrpartricipant INT NOT NULL, nbrheures INT NOT NULL, datedeb DATETIME NOT NULL, nom VARCHAR(250) NOT NULL, image VARCHAR(250) NOT NULL, descrption LONGTEXT NOT NULL, UNIQUE INDEX UNIQ_404021BF858096B0 (nomformateur_id), INDEX IDX_404021BFA21214B7 (categories_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE participants (id INT AUTO_INCREMENT NOT NULL, formation_id INT NOT NULL, date DATETIME NOT NULL, INDEX IDX_716970925200282E (formation_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE commantaire ADD CONSTRAINT FK_93BF4CAF786A81FB FOREIGN KEY (iduser_id) REFERENCES user (id)');
        $this->addSql('ALTER TABLE commantaire ADD CONSTRAINT FK_93BF4CAF14AF5727 FOREIGN KEY (idformation_id) REFERENCES formation (id)');
        $this->addSql('ALTER TABLE formation ADD CONSTRAINT FK_404021BF858096B0 FOREIGN KEY (nomformateur_id) REFERENCES formateur (id)');
        $this->addSql('ALTER TABLE formation ADD CONSTRAINT FK_404021BFA21214B7 FOREIGN KEY (categories_id) REFERENCES categories (id)');
        $this->addSql('ALTER TABLE participants ADD CONSTRAINT FK_716970925200282E FOREIGN KEY (formation_id) REFERENCES formation (id)');
        $this->addSql('ALTER TABLE user CHANGE role role VARCHAR(10) NOT NULL');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE formation DROP FOREIGN KEY FK_404021BFA21214B7');
        $this->addSql('ALTER TABLE formation DROP FOREIGN KEY FK_404021BF858096B0');
        $this->addSql('ALTER TABLE commantaire DROP FOREIGN KEY FK_93BF4CAF14AF5727');
        $this->addSql('ALTER TABLE participants DROP FOREIGN KEY FK_716970925200282E');
        $this->addSql('DROP TABLE categories');
        $this->addSql('DROP TABLE commantaire');
        $this->addSql('DROP TABLE formateur');
        $this->addSql('DROP TABLE formation');
        $this->addSql('DROP TABLE participants');
        $this->addSql('ALTER TABLE user CHANGE role role VARCHAR(10) CHARACTER SET utf8mb4 DEFAULT \'client\' NOT NULL COLLATE `utf8mb4_unicode_ci`');
    }
}
