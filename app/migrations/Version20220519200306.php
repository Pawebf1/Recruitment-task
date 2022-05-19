<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20220519200306 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE cargo ADD transport_id_id INT NOT NULL');
        $this->addSql('ALTER TABLE cargo ADD CONSTRAINT FK_3BEE5771E24B39AF FOREIGN KEY (transport_id_id) REFERENCES transport (id)');
        $this->addSql('CREATE INDEX IDX_3BEE5771E24B39AF ON cargo (transport_id_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE cargo DROP FOREIGN KEY FK_3BEE5771E24B39AF');
        $this->addSql('DROP INDEX IDX_3BEE5771E24B39AF ON cargo');
        $this->addSql('ALTER TABLE cargo DROP transport_id_id');
    }
}
