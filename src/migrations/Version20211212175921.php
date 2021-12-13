<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20211212175921 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE country (id INT AUTO_INCREMENT NOT NULL, locale_id INT NOT NULL, name VARCHAR(20) NOT NULL, INDEX IDX_5373C966E559DFD1 (locale_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE locale (id INT AUTO_INCREMENT NOT NULL, name VARCHAR(20) NOT NULL, iso_code VARCHAR(10) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE product (id INT AUTO_INCREMENT NOT NULL, vat_rate_id INT NOT NULL, name VARCHAR(50) NOT NULL, description VARCHAR(500) DEFAULT NULL, price DOUBLE PRECISION NOT NULL, INDEX IDX_D34A04AD43897540 (vat_rate_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE vat_rate (id INT AUTO_INCREMENT NOT NULL, rate DOUBLE PRECISION NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE vat_rate_country (vat_rate_id INT NOT NULL, country_id INT NOT NULL, INDEX IDX_B4D4915143897540 (vat_rate_id), INDEX IDX_B4D49151F92F3E70 (country_id), PRIMARY KEY(vat_rate_id, country_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE country ADD CONSTRAINT FK_5373C966E559DFD1 FOREIGN KEY (locale_id) REFERENCES locale (id)');
        $this->addSql('ALTER TABLE product ADD CONSTRAINT FK_D34A04AD43897540 FOREIGN KEY (vat_rate_id) REFERENCES vat_rate (id)');
        $this->addSql('ALTER TABLE vat_rate_country ADD CONSTRAINT FK_B4D4915143897540 FOREIGN KEY (vat_rate_id) REFERENCES vat_rate (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE vat_rate_country ADD CONSTRAINT FK_B4D49151F92F3E70 FOREIGN KEY (country_id) REFERENCES country (id) ON DELETE CASCADE');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE vat_rate_country DROP FOREIGN KEY FK_B4D49151F92F3E70');
        $this->addSql('ALTER TABLE country DROP FOREIGN KEY FK_5373C966E559DFD1');
        $this->addSql('ALTER TABLE product DROP FOREIGN KEY FK_D34A04AD43897540');
        $this->addSql('ALTER TABLE vat_rate_country DROP FOREIGN KEY FK_B4D4915143897540');
        $this->addSql('DROP TABLE country');
        $this->addSql('DROP TABLE locale');
        $this->addSql('DROP TABLE product');
        $this->addSql('DROP TABLE vat_rate');
        $this->addSql('DROP TABLE vat_rate_country');
    }
}
