<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20200511155719 extends AbstractMigration
{
    public function getDescription() : string
    {
        return 'Create Reservations';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'postgresql', 'Migration can only be executed safely on \'postgresql\'.');

        $this->addSql('CREATE TABLE reservations (id UUID NOT NULL, flight_id UUID DEFAULT NULL, seat INT NOT NULL, customer_id UUID NOT NULL, ticket_id UUID DEFAULT NULL, passenger_first_name VARCHAR(150) NOT NULL, passenger_last_name VARCHAR(150) NOT NULL, passenger_email_address VARCHAR(255) NOT NULL, status VARCHAR(20) NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE INDEX IDX_4DA23991F478C5 ON reservations (flight_id)');
        $this->addSql('ALTER TABLE reservations ADD CONSTRAINT FK_4DA23991F478C5 FOREIGN KEY (flight_id) REFERENCES flights (id) ON DELETE SET NULL NOT DEFERRABLE INITIALLY IMMEDIATE');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'postgresql', 'Migration can only be executed safely on \'postgresql\'.');

        $this->addSql('DROP TABLE reservations');
    }
}
