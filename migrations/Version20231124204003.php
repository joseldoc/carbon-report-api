<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20231124204003 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE SEQUENCE report_id_seq INCREMENT BY 1 MINVALUE 1 START 1');
        $this->addSql('CREATE SEQUENCE views_id_seq INCREMENT BY 1 MINVALUE 1 START 1');
        $this->addSql('CREATE TABLE report (id INT NOT NULL, created_at TIMESTAMP(0) WITHOUT TIME ZONE DEFAULT NULL, updated_at TIMESTAMP(0) WITHOUT TIME ZONE DEFAULT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE TABLE report_video (report_id INT NOT NULL, video_id INT NOT NULL, PRIMARY KEY(report_id, video_id))');
        $this->addSql('CREATE INDEX IDX_56DBDD554BD2A4C0 ON report_video (report_id)');
        $this->addSql('CREATE INDEX IDX_56DBDD5529C1004E ON report_video (video_id)');
        $this->addSql('CREATE TABLE views (id INT NOT NULL, video_id INT NOT NULL, report_id INT NOT NULL, number_views INT NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE INDEX IDX_11F09C8729C1004E ON views (video_id)');
        $this->addSql('CREATE INDEX IDX_11F09C874BD2A4C0 ON views (report_id)');
        $this->addSql('ALTER TABLE report_video ADD CONSTRAINT FK_56DBDD554BD2A4C0 FOREIGN KEY (report_id) REFERENCES report (id) ON DELETE CASCADE NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE report_video ADD CONSTRAINT FK_56DBDD5529C1004E FOREIGN KEY (video_id) REFERENCES video (id) ON DELETE CASCADE NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE views ADD CONSTRAINT FK_11F09C8729C1004E FOREIGN KEY (video_id) REFERENCES video (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE views ADD CONSTRAINT FK_11F09C874BD2A4C0 FOREIGN KEY (report_id) REFERENCES report (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE SCHEMA public');
        $this->addSql('DROP SEQUENCE report_id_seq CASCADE');
        $this->addSql('DROP SEQUENCE views_id_seq CASCADE');
        $this->addSql('ALTER TABLE report_video DROP CONSTRAINT FK_56DBDD554BD2A4C0');
        $this->addSql('ALTER TABLE report_video DROP CONSTRAINT FK_56DBDD5529C1004E');
        $this->addSql('ALTER TABLE views DROP CONSTRAINT FK_11F09C8729C1004E');
        $this->addSql('ALTER TABLE views DROP CONSTRAINT FK_11F09C874BD2A4C0');
        $this->addSql('DROP TABLE report');
        $this->addSql('DROP TABLE report_video');
        $this->addSql('DROP TABLE views');
    }
}
