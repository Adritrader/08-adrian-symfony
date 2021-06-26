<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20210626143149 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE linea_pedido ADD producto_id INT NOT NULL, ADD pedido_id INT NOT NULL');
        $this->addSql('ALTER TABLE linea_pedido ADD CONSTRAINT FK_183C31657645698E FOREIGN KEY (producto_id) REFERENCES producto (id)');
        $this->addSql('ALTER TABLE linea_pedido ADD CONSTRAINT FK_183C31654854653A FOREIGN KEY (pedido_id) REFERENCES pedidos (id)');
        $this->addSql('CREATE INDEX IDX_183C31657645698E ON linea_pedido (producto_id)');
        $this->addSql('CREATE INDEX IDX_183C31654854653A ON linea_pedido (pedido_id)');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE linea_pedido DROP FOREIGN KEY FK_183C31657645698E');
        $this->addSql('ALTER TABLE linea_pedido DROP FOREIGN KEY FK_183C31654854653A');
        $this->addSql('DROP INDEX IDX_183C31657645698E ON linea_pedido');
        $this->addSql('DROP INDEX IDX_183C31654854653A ON linea_pedido');
        $this->addSql('ALTER TABLE linea_pedido DROP producto_id, DROP pedido_id');
    }
}
