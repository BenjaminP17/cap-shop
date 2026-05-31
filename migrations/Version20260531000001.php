<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

final class Version20260531000001 extends AbstractMigration
{
    public function getDescription(): string
    {
        return 'Create product table';
    }

    public function up(Schema $schema): void
    {
        $this->addSql('CREATE TABLE product (
            id INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL,
            name VARCHAR(120) NOT NULL,
            slug VARCHAR(140) NOT NULL,
            brand VARCHAR(100) DEFAULT NULL,
            price NUMERIC(7, 2) NOT NULL,
            category VARCHAR(20) NOT NULL,
            image_url VARCHAR(255) DEFAULT NULL,
            badge VARCHAR(20) DEFAULT NULL,
            stock INTEGER NOT NULL,
            sold_count INTEGER NOT NULL,
            is_bestseller BOOLEAN NOT NULL,
            is_active BOOLEAN NOT NULL,
            dropped_at DATETIME DEFAULT NULL,
            description VARCHAR(255) DEFAULT NULL
        )');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_D34A04AD989D9B62 ON product (slug)');
    }

    public function down(Schema $schema): void
    {
        $this->addSql('DROP TABLE product');
    }
}
