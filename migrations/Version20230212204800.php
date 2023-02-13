<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20230212204800 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE dish (name VARCHAR(255) NOT NULL, description LONGTEXT NOT NULL, price DOUBLE PRECISION NOT NULL, picture VARBINARY(255) DEFAULT NULL, Menu VARCHAR(255) NOT NULL, INDEX IDX_957D8CB8DD3795AD (Menu), PRIMARY KEY(name)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE menu (type VARCHAR(255) NOT NULL, PRIMARY KEY(type)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE reservation (id INT AUTO_INCREMENT NOT NULL, mail VARCHAR(255) DEFAULT NULL, surname VARCHAR(255) NOT NULL, name VARCHAR(255) NOT NULL, phone_number VARCHAR(255) DEFAULT NULL, seat_reserved INT NOT NULL, hour TIME NOT NULL, ReservationDate VARCHAR(255) NOT NULL, INDEX IDX_42C849559A0092FF (ReservationDate), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE reservation_date (reservation_date VARCHAR(255) NOT NULL, PRIMARY KEY(reservation_date)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE seats (id INT AUTO_INCREMENT NOT NULL, hour TIME NOT NULL, seat INT NOT NULL, ReservationDate VARCHAR(255) NOT NULL, INDEX IDX_BFE257509A0092FF (ReservationDate), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE messenger_messages (id BIGINT AUTO_INCREMENT NOT NULL, body LONGTEXT NOT NULL, headers LONGTEXT NOT NULL, queue_name VARCHAR(190) NOT NULL, created_at DATETIME NOT NULL, available_at DATETIME NOT NULL, delivered_at DATETIME DEFAULT NULL, INDEX IDX_75EA56E0FB7336F0 (queue_name), INDEX IDX_75EA56E0E3BD61CE (available_at), INDEX IDX_75EA56E016BA31DB (delivered_at), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE dish ADD CONSTRAINT FK_957D8CB8DD3795AD FOREIGN KEY (Menu) REFERENCES menu (type)');
        $this->addSql('ALTER TABLE reservation ADD CONSTRAINT FK_42C849559A0092FF FOREIGN KEY (ReservationDate) REFERENCES reservation_date (reservation_date)');
        $this->addSql('ALTER TABLE seats ADD CONSTRAINT FK_BFE257509A0092FF FOREIGN KEY (ReservationDate) REFERENCES reservation_date (reservation_date)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE dish DROP FOREIGN KEY FK_957D8CB8DD3795AD');
        $this->addSql('ALTER TABLE reservation DROP FOREIGN KEY FK_42C849559A0092FF');
        $this->addSql('ALTER TABLE seats DROP FOREIGN KEY FK_BFE257509A0092FF');
        $this->addSql('DROP TABLE dish');
        $this->addSql('DROP TABLE menu');
        $this->addSql('DROP TABLE reservation');
        $this->addSql('DROP TABLE reservation_date');
        $this->addSql('DROP TABLE seats');
        $this->addSql('DROP TABLE messenger_messages');
    }
}
