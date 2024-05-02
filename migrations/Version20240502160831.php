<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20240502160831 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('DROP TABLE oeuvre');
        $this->addSql('DROP TABLE vente');
        $this->addSql('ALTER TABLE spins ADD CONSTRAINT FK_661B5B9EFB88E14F FOREIGN KEY (utilisateur_id) REFERENCES utilisateurs (utilisateur_id)');
        $this->addSql('DROP INDEX fk_utilisateur ON spins');
        $this->addSql('CREATE INDEX IDX_661B5B9EFB88E14F ON spins (utilisateur_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE oeuvre (id INT AUTO_INCREMENT NOT NULL, titre VARCHAR(255) CHARACTER SET utf8mb4 NOT NULL COLLATE `utf8mb4_general_ci`, description TEXT CHARACTER SET utf8mb4 NOT NULL COLLATE `utf8mb4_general_ci`, disponibilite VARCHAR(255) CHARACTER SET utf8mb4 NOT NULL COLLATE `utf8mb4_general_ci`, prix DOUBLE PRECISION NOT NULL, dateCreation DATE NOT NULL, type VARCHAR(255) CHARACTER SET utf8mb4 NOT NULL COLLATE `utf8mb4_general_ci`, posterUrl VARCHAR(255) CHARACTER SET utf8mb4 NOT NULL COLLATE `utf8mb4_general_ci`, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_general_ci` ENGINE = InnoDB COMMENT = \'\' ');
        $this->addSql('CREATE TABLE vente (ID INT AUTO_INCREMENT NOT NULL, ID_OeuvreVendue INT DEFAULT NULL, DateVente DATE DEFAULT NULL, PrixVente DOUBLE PRECISION DEFAULT NULL, ModePaiement VARCHAR(255) CHARACTER SET utf8mb4 DEFAULT NULL COLLATE `utf8mb4_general_ci`, quantite INT DEFAULT NULL, INDEX ID_OeuvreVendue (ID_OeuvreVendue), PRIMARY KEY(ID)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_general_ci` ENGINE = InnoDB COMMENT = \'\' ');
        $this->addSql('ALTER TABLE spins DROP FOREIGN KEY FK_661B5B9EFB88E14F');
        $this->addSql('ALTER TABLE spins DROP FOREIGN KEY FK_661B5B9EFB88E14F');
        $this->addSql('DROP INDEX idx_661b5b9efb88e14f ON spins');
        $this->addSql('CREATE INDEX fk_utilisateur ON spins (utilisateur_id)');
        $this->addSql('ALTER TABLE spins ADD CONSTRAINT FK_661B5B9EFB88E14F FOREIGN KEY (utilisateur_id) REFERENCES utilisateurs (utilisateur_id)');
    }
}
