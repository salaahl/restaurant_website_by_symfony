<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20241224050242 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        $this->addSql('
            CREATE TABLE menu (
                id SERIAL PRIMARY KEY,
                name VARCHAR(255) NOT NULL UNIQUE
            )
        ');
    
        $this->addSql('
            CREATE TABLE dish (
                id SERIAL PRIMARY KEY,
                menu_id INTEGER NOT NULL,
                name VARCHAR(255) NOT NULL,
                description TEXT NOT NULL,
                price DOUBLE PRECISION NOT NULL,
                CONSTRAINT FK_957D8CB8CCD7E912 FOREIGN KEY (menu_id) REFERENCES menu (id) ON DELETE CASCADE
            )
        ');
    
        $this->addSql('
            CREATE TABLE reservation_date (
                id SERIAL PRIMARY KEY,
                date TIMESTAMP NOT NULL UNIQUE
            )
        ');
    
        $this->addSql('
            CREATE TABLE reservation (
                id SERIAL PRIMARY KEY,
                date_id INTEGER NOT NULL,
                email VARCHAR(255) NOT NULL,
                surname VARCHAR(255) NOT NULL,
                name VARCHAR(255) NOT NULL,
                phone_number VARCHAR(255),
                seats INTEGER NOT NULL,
                hour DOUBLE PRECISION NOT NULL,
                CONSTRAINT FK_42C84955B897366B FOREIGN KEY (date_id) REFERENCES reservation_date (id)
            )
        ');
    
        $this->addSql('
            CREATE TABLE seat (
                id SERIAL PRIMARY KEY,
                reservation_date_id INTEGER NOT NULL,
                hour DOUBLE PRECISION NOT NULL,
                seats_available INTEGER NOT NULL,
                CONSTRAINT FK_3D5C3666DF028DEE FOREIGN KEY (reservation_date_id) REFERENCES reservation_date (id)
            )
        ');
    
        $this->addSql('
            CREATE TABLE messenger_messages (
                id BIGSERIAL PRIMARY KEY,
                body TEXT NOT NULL,
                headers TEXT NOT NULL,
                queue_name VARCHAR(190) NOT NULL,
                created_at TIMESTAMP NOT NULL,
                available_at TIMESTAMP NOT NULL,
                delivered_at TIMESTAMP DEFAULT NULL
            )
        ');
        $this->addSql('CREATE INDEX IDX_75EA56E0FB7336F0 ON messenger_messages (queue_name)');
        $this->addSql('CREATE INDEX IDX_75EA56E0E3BD61CE ON messenger_messages (available_at)');
        $this->addSql('CREATE INDEX IDX_75EA56E016BA31DB ON messenger_messages (delivered_at)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE dish DROP FOREIGN KEY FK_957D8CB8CCD7E912');
        $this->addSql('ALTER TABLE reservation DROP FOREIGN KEY FK_42C84955B897366B');
        $this->addSql('ALTER TABLE seat DROP FOREIGN KEY FK_3D5C3666DF028DEE');
        $this->addSql('DROP TABLE dish');
        $this->addSql('DROP TABLE menu');
        $this->addSql('DROP TABLE reservation');
        $this->addSql('DROP TABLE reservation_date');
        $this->addSql('DROP TABLE seat');
        $this->addSql('DROP TABLE messenger_messages');
    }
}
