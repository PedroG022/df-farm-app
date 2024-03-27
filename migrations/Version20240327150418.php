<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20240327150418 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE cattle (id BINARY(16) NOT NULL COMMENT \'(DC2Type:uuid)\', farm_id BINARY(16) NOT NULL COMMENT \'(DC2Type:uuid)\', code VARCHAR(64) NOT NULL, milk_per_week DOUBLE PRECISION NOT NULL, feed DOUBLE PRECISION NOT NULL, weight DOUBLE PRECISION NOT NULL, birthdate DATETIME NOT NULL, UNIQUE INDEX UNIQ_D8C5C49177153098 (code), INDEX IDX_D8C5C49165FCFA0D (farm_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE farm (id BINARY(16) NOT NULL COMMENT \'(DC2Type:uuid)\', name VARCHAR(255) NOT NULL, hectares DOUBLE PRECISION NOT NULL, responsible VARCHAR(255) NOT NULL, UNIQUE INDEX UNIQ_5816D0455E237E06 (name), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE farm_veterinarian (farm_id BINARY(16) NOT NULL COMMENT \'(DC2Type:uuid)\', veterinarian_id BINARY(16) NOT NULL COMMENT \'(DC2Type:uuid)\', INDEX IDX_499A5CC65FCFA0D (farm_id), INDEX IDX_499A5CC804C8213 (veterinarian_id), PRIMARY KEY(farm_id, veterinarian_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE veterinarian (id BINARY(16) NOT NULL COMMENT \'(DC2Type:uuid)\', name VARCHAR(255) NOT NULL, crmv VARCHAR(64) NOT NULL, UNIQUE INDEX UNIQ_4E5C18053697FA2C (crmv), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE cattle ADD CONSTRAINT FK_D8C5C49165FCFA0D FOREIGN KEY (farm_id) REFERENCES farm (id)');
        $this->addSql('ALTER TABLE farm_veterinarian ADD CONSTRAINT FK_499A5CC65FCFA0D FOREIGN KEY (farm_id) REFERENCES farm (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE farm_veterinarian ADD CONSTRAINT FK_499A5CC804C8213 FOREIGN KEY (veterinarian_id) REFERENCES veterinarian (id) ON DELETE CASCADE');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE cattle DROP FOREIGN KEY FK_D8C5C49165FCFA0D');
        $this->addSql('ALTER TABLE farm_veterinarian DROP FOREIGN KEY FK_499A5CC65FCFA0D');
        $this->addSql('ALTER TABLE farm_veterinarian DROP FOREIGN KEY FK_499A5CC804C8213');
        $this->addSql('DROP TABLE cattle');
        $this->addSql('DROP TABLE farm');
        $this->addSql('DROP TABLE farm_veterinarian');
        $this->addSql('DROP TABLE veterinarian');
    }
}
