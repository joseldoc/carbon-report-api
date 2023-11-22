<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20231122061719 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE SEQUENCE folder_id_seq INCREMENT BY 1 MINVALUE 1 START 1');
        $this->addSql('CREATE TABLE folder (id INT NOT NULL, dossier VARCHAR(255) NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE TABLE folder_video (folder_id INT NOT NULL, video_id INT NOT NULL, PRIMARY KEY(folder_id, video_id))');
        $this->addSql('CREATE INDEX IDX_1103A1E1162CB942 ON folder_video (folder_id)');
        $this->addSql('CREATE INDEX IDX_1103A1E129C1004E ON folder_video (video_id)');
        $this->addSql('ALTER TABLE folder_video ADD CONSTRAINT FK_1103A1E1162CB942 FOREIGN KEY (folder_id) REFERENCES folder (id) ON DELETE CASCADE NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE folder_video ADD CONSTRAINT FK_1103A1E129C1004E FOREIGN KEY (video_id) REFERENCES video (id) ON DELETE CASCADE NOT DEFERRABLE INITIALLY IMMEDIATE');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE SCHEMA public');
        $this->addSql('DROP SEQUENCE folder_id_seq CASCADE');
        $this->addSql('ALTER TABLE folder_video DROP CONSTRAINT FK_1103A1E1162CB942');
        $this->addSql('ALTER TABLE folder_video DROP CONSTRAINT FK_1103A1E129C1004E');
        $this->addSql('DROP TABLE folder');
        $this->addSql('DROP TABLE folder_video');
    }
}
