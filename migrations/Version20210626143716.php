<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20210626143716 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE pedidos ADD linea_pedido_id INT NOT NULL');
        $this->addSql('ALTER TABLE pedidos ADD CONSTRAINT FK_6716CCAAC16FB7D6 FOREIGN KEY (linea_pedido_id) REFERENCES linea_pedido (id)');
        $this->addSql('CREATE INDEX IDX_6716CCAAC16FB7D6 ON pedidos (linea_pedido_id)');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE pedidos DROP FOREIGN KEY FK_6716CCAAC16FB7D6');
        $this->addSql('DROP INDEX IDX_6716CCAAC16FB7D6 ON pedidos');
        $this->addSql('ALTER TABLE pedidos DROP linea_pedido_id');
    }
}
