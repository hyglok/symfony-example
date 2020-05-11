<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20200511190618 extends AbstractMigration
{
    public function getDescription() : string
    {
        return 'Delete flight relation';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'postgresql', 'Migration can only be executed safely on \'postgresql\'.');

        $this->addSql('ALTER TABLE reservations DROP CONSTRAINT fk_4da23991f478c5');
        $this->addSql('DROP INDEX idx_4da23991f478c5');
        $this->addSql('ALTER TABLE reservations ALTER flight_id SET NOT NULL');
        $this->addSql('ALTER TABLE tickets DROP CONSTRAINT fk_54469df491f478c5');
        $this->addSql('DROP INDEX idx_54469df491f478c5');
        $this->addSql('ALTER TABLE tickets ALTER flight_id SET NOT NULL');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'postgresql', 'Migration can only be executed safely on \'postgresql\'.');

        $this->addSql('ALTER TABLE tickets ALTER flight_id DROP NOT NULL');
        $this->addSql('ALTER TABLE tickets ADD CONSTRAINT fk_54469df491f478c5 FOREIGN KEY (flight_id) REFERENCES flights (id) ON DELETE SET NULL NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('CREATE INDEX idx_54469df491f478c5 ON tickets (flight_id)');
        $this->addSql('ALTER TABLE reservations ALTER flight_id DROP NOT NULL');
        $this->addSql('ALTER TABLE reservations ADD CONSTRAINT fk_4da23991f478c5 FOREIGN KEY (flight_id) REFERENCES flights (id) ON DELETE SET NULL NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('CREATE INDEX idx_4da23991f478c5 ON reservations (flight_id)');
    }
}
