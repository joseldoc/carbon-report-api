<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20231120190225 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE SEQUENCE video_encode_id_seq INCREMENT BY 1 MINVALUE 1 START 1');
        $this->addSql('CREATE TABLE video_encode (id INT NOT NULL, video_id INT NOT NULL, size BIGINT NOT NULL, quality VARCHAR(100) NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_A1FFB92F29C1004E ON video_encode (video_id)');
        $this->addSql('ALTER TABLE video_encode ADD CONSTRAINT FK_A1FFB92F29C1004E FOREIGN KEY (video_id) REFERENCES video (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE SCHEMA public');
        $this->addSql('DROP SEQUENCE video_encode_id_seq CASCADE');
        $this->addSql('ALTER TABLE video_encode DROP CONSTRAINT FK_A1FFB92F29C1004E');
        $this->addSql('DROP TABLE video_encode');
    }
}
