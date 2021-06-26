<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20210614231812 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE contiene_pro_pedido DROP FOREIGN KEY FK_28044CF73A9534E0');
        $this->addSql('ALTER TABLE contiene_pro_producto DROP FOREIGN KEY FK_2281DFAD3A9534E0');
        $this->addSql('DROP TABLE contiene_pro');
        $this->addSql('DROP TABLE contiene_pro_pedido');
        $this->addSql('DROP TABLE contiene_pro_producto');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE contiene_pro (id INT AUTO_INCREMENT NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE `utf8_unicode_ci` ENGINE = InnoDB COMMENT = \'\' ');
        $this->addSql('CREATE TABLE contiene_pro_pedido (contiene_pro_id INT NOT NULL, pedido_id INT NOT NULL, INDEX IDX_28044CF73A9534E0 (contiene_pro_id), INDEX IDX_28044CF74854653A (pedido_id), PRIMARY KEY(contiene_pro_id, pedido_id)) DEFAULT CHARACTER SET utf8 COLLATE `utf8_unicode_ci` ENGINE = InnoDB COMMENT = \'\' ');
        $this->addSql('CREATE TABLE contiene_pro_producto (contiene_pro_id INT NOT NULL, producto_id INT NOT NULL, INDEX IDX_2281DFAD3A9534E0 (contiene_pro_id), INDEX IDX_2281DFAD7645698E (producto_id), PRIMARY KEY(contiene_pro_id, producto_id)) DEFAULT CHARACTER SET utf8 COLLATE `utf8_unicode_ci` ENGINE = InnoDB COMMENT = \'\' ');
        $this->addSql('ALTER TABLE contiene_pro_pedido ADD CONSTRAINT FK_28044CF73A9534E0 FOREIGN KEY (contiene_pro_id) REFERENCES contiene_pro (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE contiene_pro_pedido ADD CONSTRAINT FK_28044CF74854653A FOREIGN KEY (pedido_id) REFERENCES pedido (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE contiene_pro_producto ADD CONSTRAINT FK_2281DFAD3A9534E0 FOREIGN KEY (contiene_pro_id) REFERENCES contiene_pro (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE contiene_pro_producto ADD CONSTRAINT FK_2281DFAD7645698E FOREIGN KEY (producto_id) REFERENCES producto (id) ON DELETE CASCADE');
    }
}
