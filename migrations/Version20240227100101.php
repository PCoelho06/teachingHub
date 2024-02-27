<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20240227100101 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE document_level (document_id INT NOT NULL, level_id INT NOT NULL, INDEX IDX_F34BBA61C33F7837 (document_id), INDEX IDX_F34BBA615FB14BA7 (level_id), PRIMARY KEY(document_id, level_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE document_subject (document_id INT NOT NULL, subject_id INT NOT NULL, INDEX IDX_5FA198A6C33F7837 (document_id), INDEX IDX_5FA198A623EDC87 (subject_id), PRIMARY KEY(document_id, subject_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE document_theme (document_id INT NOT NULL, theme_id INT NOT NULL, INDEX IDX_FED4917AC33F7837 (document_id), INDEX IDX_FED4917A59027487 (theme_id), PRIMARY KEY(document_id, theme_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE document_level ADD CONSTRAINT FK_F34BBA61C33F7837 FOREIGN KEY (document_id) REFERENCES document (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE document_level ADD CONSTRAINT FK_F34BBA615FB14BA7 FOREIGN KEY (level_id) REFERENCES level (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE document_subject ADD CONSTRAINT FK_5FA198A6C33F7837 FOREIGN KEY (document_id) REFERENCES document (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE document_subject ADD CONSTRAINT FK_5FA198A623EDC87 FOREIGN KEY (subject_id) REFERENCES subject (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE document_theme ADD CONSTRAINT FK_FED4917AC33F7837 FOREIGN KEY (document_id) REFERENCES document (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE document_theme ADD CONSTRAINT FK_FED4917A59027487 FOREIGN KEY (theme_id) REFERENCES theme (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE theme_document DROP FOREIGN KEY FK_7A57980159027487');
        $this->addSql('ALTER TABLE theme_document DROP FOREIGN KEY FK_7A579801C33F7837');
        $this->addSql('ALTER TABLE level_document DROP FOREIGN KEY FK_55C02FA65FB14BA7');
        $this->addSql('ALTER TABLE level_document DROP FOREIGN KEY FK_55C02FA6C33F7837');
        $this->addSql('ALTER TABLE subject_document DROP FOREIGN KEY FK_E014CA1E23EDC87');
        $this->addSql('ALTER TABLE subject_document DROP FOREIGN KEY FK_E014CA1EC33F7837');
        $this->addSql('DROP TABLE theme_document');
        $this->addSql('DROP TABLE level_document');
        $this->addSql('DROP TABLE subject_document');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE theme_document (theme_id INT NOT NULL, document_id INT NOT NULL, INDEX IDX_7A57980159027487 (theme_id), INDEX IDX_7A579801C33F7837 (document_id), PRIMARY KEY(theme_id, document_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB COMMENT = \'\' ');
        $this->addSql('CREATE TABLE level_document (level_id INT NOT NULL, document_id INT NOT NULL, INDEX IDX_55C02FA65FB14BA7 (level_id), INDEX IDX_55C02FA6C33F7837 (document_id), PRIMARY KEY(level_id, document_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB COMMENT = \'\' ');
        $this->addSql('CREATE TABLE subject_document (subject_id INT NOT NULL, document_id INT NOT NULL, INDEX IDX_E014CA1E23EDC87 (subject_id), INDEX IDX_E014CA1EC33F7837 (document_id), PRIMARY KEY(subject_id, document_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB COMMENT = \'\' ');
        $this->addSql('ALTER TABLE theme_document ADD CONSTRAINT FK_7A57980159027487 FOREIGN KEY (theme_id) REFERENCES theme (id) ON UPDATE NO ACTION ON DELETE CASCADE');
        $this->addSql('ALTER TABLE theme_document ADD CONSTRAINT FK_7A579801C33F7837 FOREIGN KEY (document_id) REFERENCES document (id) ON UPDATE NO ACTION ON DELETE CASCADE');
        $this->addSql('ALTER TABLE level_document ADD CONSTRAINT FK_55C02FA65FB14BA7 FOREIGN KEY (level_id) REFERENCES level (id) ON UPDATE NO ACTION ON DELETE CASCADE');
        $this->addSql('ALTER TABLE level_document ADD CONSTRAINT FK_55C02FA6C33F7837 FOREIGN KEY (document_id) REFERENCES document (id) ON UPDATE NO ACTION ON DELETE CASCADE');
        $this->addSql('ALTER TABLE subject_document ADD CONSTRAINT FK_E014CA1E23EDC87 FOREIGN KEY (subject_id) REFERENCES subject (id) ON UPDATE NO ACTION ON DELETE CASCADE');
        $this->addSql('ALTER TABLE subject_document ADD CONSTRAINT FK_E014CA1EC33F7837 FOREIGN KEY (document_id) REFERENCES document (id) ON UPDATE NO ACTION ON DELETE CASCADE');
        $this->addSql('ALTER TABLE document_level DROP FOREIGN KEY FK_F34BBA61C33F7837');
        $this->addSql('ALTER TABLE document_level DROP FOREIGN KEY FK_F34BBA615FB14BA7');
        $this->addSql('ALTER TABLE document_subject DROP FOREIGN KEY FK_5FA198A6C33F7837');
        $this->addSql('ALTER TABLE document_subject DROP FOREIGN KEY FK_5FA198A623EDC87');
        $this->addSql('ALTER TABLE document_theme DROP FOREIGN KEY FK_FED4917AC33F7837');
        $this->addSql('ALTER TABLE document_theme DROP FOREIGN KEY FK_FED4917A59027487');
        $this->addSql('DROP TABLE document_level');
        $this->addSql('DROP TABLE document_subject');
        $this->addSql('DROP TABLE document_theme');
    }
}
