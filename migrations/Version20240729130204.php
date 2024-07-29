<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20240729130204 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        $this->addSql("INSERT INTO product (name, price) VALUES ('iPhone', 100.00);");
        $this->addSql("INSERT INTO product (name, price) VALUES ('Наушники', 20.00);");
        $this->addSql("INSERT INTO product (name, price) VALUES ('Чехол', 10.00);");

        $this->addSql("INSERT INTO tax (country, rate) VALUES ('DE', 19);");
        $this->addSql("INSERT INTO tax (country, rate) VALUES ('IT', 22);");
        $this->addSql("INSERT INTO tax (country, rate) VALUES ('FR', 20);");
        $this->addSql("INSERT INTO tax (country, rate) VALUES ('GR', 24);");

        $this->addSql("INSERT INTO coupon (code, discount_type, discount_value) VALUES ('D15', 'fixedDiscountAmount', 15);");
        $this->addSql("INSERT INTO coupon (code, discount_type, discount_value) VALUES ('P100', 'purchaseAmountPercentage', 100);");
        $this->addSql("INSERT INTO coupon (code, discount_type, discount_value) VALUES ('P10', 'purchaseAmountPercentage', 10);");

        $this->addSql("INSERT INTO payment_processor (name) VALUES ('paypal');");
        $this->addSql("INSERT INTO payment_processor (name) VALUES ('stripe');");

    }

    public function down(Schema $schema): void
    {

    }
}
