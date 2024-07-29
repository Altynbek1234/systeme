<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20240726151134 extends AbstractMigration
{
    public function getDescription(): string
    {
        return 'Создание таблиц coupon, order, product и tax';
    }

    public function up(Schema $schema) : void
    {
        $this->addSql('CREATE TABLE coupon (
        id SERIAL PRIMARY KEY, 
        code VARCHAR(255) NOT NULL, 
        discount_type VARCHAR(255) NOT NULL, 
        discount_value NUMERIC(5, 2) NOT NULL
    )');

        $this->addSql('CREATE TABLE "order" (
        id SERIAL PRIMARY KEY, 
        product_id INT NOT NULL, 
        tax_number VARCHAR(255) NOT NULL, 
        coupon_code VARCHAR(255) DEFAULT NULL, 
        payment_processor VARCHAR(255) NOT NULL, 
        total_price VARCHAR(255) NOT NULL
    )');

        $this->addSql('CREATE TABLE product (
        id SERIAL PRIMARY KEY, 
        name VARCHAR(255) NOT NULL, 
        price NUMERIC(10, 2) NOT NULL
    )');

        $this->addSql('CREATE TABLE tax (
        id SERIAL PRIMARY KEY, 
        country VARCHAR(255) NOT NULL, 
        rate NUMERIC(5, 2) NOT NULL
    )');
    }

    public function down(Schema $schema) : void
    {
        $this->addSql('DROP TABLE coupon');
        $this->addSql('DROP TABLE "order"');
        $this->addSql('DROP TABLE product');
        $this->addSql('DROP TABLE tax');
    }

}
