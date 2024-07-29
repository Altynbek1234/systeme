<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20240729104651 extends AbstractMigration
{
    public function getDescription(): string
    {
        return 'created payment_processor table';
    }

    public function up(Schema $schema): void
    {
        $this->addSql('CREATE TABLE payment_processor (
            id SERIAL PRIMARY KEY,
            name VARCHAR(50) NOT NULL,
            UNIQUE (name)
        )');
    }

    public function down(Schema $schema): void
    {
        $this->addSql('DROP TABLE payment_processor');
    }
}
