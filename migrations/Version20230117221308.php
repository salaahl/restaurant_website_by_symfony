<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20230117221308 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE dish (type_id VARCHAR(50) NOT NULL, name VARCHAR(100) NOT NULL, description LONGTEXT NOT NULL, price VARCHAR(5) NOT NULL, pictures LONGBLOB NOT NULL, INDEX IDX_957D8CB8C54C8C93 (type_id), PRIMARY KEY(name)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE menu (type VARCHAR(50) NOT NULL, PRIMARY KEY(type)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE reservation (date_id DATE NOT NULL, mail VARCHAR(100) NOT NULL, surname VARCHAR(50) NOT NULL, name VARCHAR(50) NOT NULL, phone_number VARCHAR(20) NOT NULL, seats_reserved SMALLINT NOT NULL, INDEX IDX_42C84955B897366B (date_id), PRIMARY KEY(mail)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE restaurant (date DATE NOT NULL, PRIMARY KEY(date)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE messenger_messages (id BIGINT AUTO_INCREMENT NOT NULL, body LONGTEXT NOT NULL, headers LONGTEXT NOT NULL, queue_name VARCHAR(190) NOT NULL, created_at DATETIME NOT NULL, available_at DATETIME NOT NULL, delivered_at DATETIME DEFAULT NULL, INDEX IDX_75EA56E0FB7336F0 (queue_name), INDEX IDX_75EA56E0E3BD61CE (available_at), INDEX IDX_75EA56E016BA31DB (delivered_at), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE dish ADD CONSTRAINT FK_957D8CB8C54C8C93 FOREIGN KEY (type_id) REFERENCES menu (type)');
        $this->addSql('ALTER TABLE reservation ADD CONSTRAINT FK_42C84955B897366B FOREIGN KEY (date_id) REFERENCES restaurant (date)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE dish DROP FOREIGN KEY FK_957D8CB8C54C8C93');
        $this->addSql('ALTER TABLE reservation DROP FOREIGN KEY FK_42C84955B897366B');
        $this->addSql('DROP TABLE dish');
        $this->addSql('DROP TABLE menu');
        $this->addSql('DROP TABLE reservation');
        $this->addSql('DROP TABLE restaurant');
        $this->addSql('DROP TABLE messenger_messages');
    }
}
