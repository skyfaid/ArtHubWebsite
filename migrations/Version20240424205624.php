<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20240424205624 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE articles DROP FOREIGN KEY FK_BFDD3168FB88E14F');
        $this->addSql('DROP INDEX utilisateur_id ON articles');
        $this->addSql('CREATE INDEX IDX_BFDD3168FB88E14F ON articles (utilisateur_id)');
        $this->addSql('ALTER TABLE articles ADD CONSTRAINT FK_BFDD3168FB88E14F FOREIGN KEY (utilisateur_id) REFERENCES utilisateurs (utilisateur_id) ON DELETE CASCADE');
        $this->addSql('DROP INDEX pseudo ON utilisateurs');
        $this->addSql('DROP INDEX email ON utilisateurs');
        $this->addSql('ALTER TABLE utilisateurs ADD reset_code_expires DATETIME DEFAULT NULL, CHANGE estActif estactif TINYINT(1) DEFAULT NULL');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE articles DROP FOREIGN KEY FK_BFDD3168FB88E14F');
        $this->addSql('DROP INDEX idx_bfdd3168fb88e14f ON articles');
        $this->addSql('CREATE INDEX utilisateur_id ON articles (utilisateur_id)');
        $this->addSql('ALTER TABLE articles ADD CONSTRAINT FK_BFDD3168FB88E14F FOREIGN KEY (utilisateur_id) REFERENCES utilisateurs (utilisateur_id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE utilisateurs DROP reset_code_expires, CHANGE estactif estActif TINYINT(1) DEFAULT 0');
        $this->addSql('CREATE UNIQUE INDEX pseudo ON utilisateurs (pseudo)');
        $this->addSql('CREATE UNIQUE INDEX email ON utilisateurs (email)');
    }
}
