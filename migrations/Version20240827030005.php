<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20240827030005 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE reservation DROP FOREIGN KEY FK_42C849559A0092FF');
        $this->addSql('DROP INDEX IDX_42C849559A0092FF ON reservation');
        $this->addSql('ALTER TABLE reservation ADD reservation_date_id INT NOT NULL, DROP ReservationDate');
        $this->addSql('ALTER TABLE reservation ADD CONSTRAINT FK_42C84955DF028DEE FOREIGN KEY (reservation_date_id) REFERENCES reservation_date (id)');
        $this->addSql('CREATE INDEX IDX_42C84955DF028DEE ON reservation (reservation_date_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE reservation DROP FOREIGN KEY FK_42C84955DF028DEE');
        $this->addSql('DROP INDEX IDX_42C84955DF028DEE ON reservation');
        $this->addSql('ALTER TABLE reservation ADD ReservationDate VARCHAR(255) NOT NULL, DROP reservation_date_id');
        $this->addSql('ALTER TABLE reservation ADD CONSTRAINT FK_42C849559A0092FF FOREIGN KEY (ReservationDate) REFERENCES reservation_date (date)');
        $this->addSql('CREATE INDEX IDX_42C849559A0092FF ON reservation (ReservationDate)');
    }
}
