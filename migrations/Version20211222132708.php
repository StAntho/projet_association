<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
<<<<<<<< HEAD:migrations/Version20211222143937.php
final class Version20211222143937 extends AbstractMigration
========
final class Version20211222132708 extends AbstractMigration
>>>>>>>> 22d7663 (add contraint  validator and message flash):migrations/Version20211222132708.php
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
<<<<<<<< HEAD:migrations/Version20211222143937.php
        $this->addSql('CREATE TABLE animal (id INT AUTO_INCREMENT NOT NULL, name VARCHAR(255) NOT NULL, age INT NOT NULL, type VARCHAR(255) NOT NULL, race VARCHAR(255) NOT NULL, description LONGTEXT NOT NULL, gender VARCHAR(20) NOT NULL, sterilised TINYINT(1) NOT NULL, reserved TINYINT(1) NOT NULL, date_arrived DATETIME NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE command (id INT AUTO_INCREMENT NOT NULL, status_command INT NOT NULL, date DATETIME NOT NULL, total_price NUMERIC(10, 2) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
========
        $this->addSql('CREATE TABLE animal (id INT AUTO_INCREMENT NOT NULL, name VARCHAR(255) NOT NULL, age INT NOT NULL, type VARCHAR(255) NOT NULL, race VARCHAR(255) NOT NULL, description LONGTEXT NOT NULL, gender VARCHAR(20) NOT NULL, sterilised TINYINT(1) NOT NULL, reserved TINYINT(1) NOT NULL, date_arrived DATETIME NOT NULL, picture VARCHAR(40) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE category (id INT AUTO_INCREMENT NOT NULL, name VARCHAR(255) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE product (id INT AUTO_INCREMENT NOT NULL, kind VARCHAR(255) NOT NULL, type VARCHAR(255) NOT NULL, price NUMERIC(10, 2) NOT NULL, quantity INT NOT NULL, image VARCHAR(255) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
>>>>>>>> 22d7663 (add contraint  validator and message flash):migrations/Version20211222132708.php
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('DROP TABLE animal');
<<<<<<<< HEAD:migrations/Version20211222143937.php
        $this->addSql('DROP TABLE command');
========
        $this->addSql('DROP TABLE category');
        $this->addSql('DROP TABLE product');
>>>>>>>> 22d7663 (add contraint  validator and message flash):migrations/Version20211222132708.php
    }
}
