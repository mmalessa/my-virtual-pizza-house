<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

final class Version20231025175255 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        $this->addSql('CREATE SCHEMA eventstore');
        $this->addSql('CREATE TABLE eventstore.serving_messages (
            id SERIAL PRIMARY KEY ,
            event_id UUID NOT NULL, 
            aggregate_root_id BYTEA NOT NULL,
            version BIGINT NULL,
            payload TEXT
            )');
        $this->addSql('CREATE UNIQUE INDEX serving_messages_reconstruction 
            ON eventstore.serving_messages (aggregate_root_id, version)');
    }

    public function down(Schema $schema): void
    {
        $this->addSql('DROP INDEX eventstore.serving_messages_reconstruction');
        $this->addSql('DROP TABLE eventstore.serving_messages');
        $this->addSql('DROP SCHEMA eventstore');
    }
}
