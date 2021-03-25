<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20210325190932 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE carts (id UUID NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE TABLE carts_products (cart_id UUID NOT NULL, product_id UUID NOT NULL, PRIMARY KEY(cart_id, product_id))');
        $this->addSql('CREATE INDEX IDX_12E5DBFB1AD5CDBF ON carts_products (cart_id)');
        $this->addSql('CREATE INDEX IDX_12E5DBFB4584665A ON carts_products (product_id)');
        $this->addSql('ALTER TABLE carts_products ADD CONSTRAINT FK_12E5DBFB1AD5CDBF FOREIGN KEY (cart_id) REFERENCES carts (id) ON DELETE CASCADE NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE carts_products ADD CONSTRAINT FK_12E5DBFB4584665A FOREIGN KEY (product_id) REFERENCES products (id) ON DELETE CASCADE NOT DEFERRABLE INITIALLY IMMEDIATE');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE SCHEMA public');
        $this->addSql('ALTER TABLE carts_products DROP CONSTRAINT FK_12E5DBFB1AD5CDBF');
        $this->addSql('DROP TABLE carts');
        $this->addSql('DROP TABLE carts_products');
    }
}
