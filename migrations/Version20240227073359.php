<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20240227073359 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE document (id INT AUTO_INCREMENT NOT NULL, type_id INT NOT NULL, title VARCHAR(255) NOT NULL, description LONGTEXT NOT NULL, file VARCHAR(255) NOT NULL, rating_average DOUBLE PRECISION DEFAULT NULL, uploaded_at DATETIME NOT NULL COMMENT \'(DC2Type:datetime_immutable)\', updated_at DATETIME DEFAULT NULL COMMENT \'(DC2Type:datetime_immutable)\', slug VARCHAR(255) NOT NULL, INDEX IDX_D8698A76C54C8C93 (type_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE level (id INT AUTO_INCREMENT NOT NULL, name VARCHAR(255) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE level_document (level_id INT NOT NULL, document_id INT NOT NULL, INDEX IDX_55C02FA65FB14BA7 (level_id), INDEX IDX_55C02FA6C33F7837 (document_id), PRIMARY KEY(level_id, document_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE subject (id INT AUTO_INCREMENT NOT NULL, name VARCHAR(255) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE subject_level (subject_id INT NOT NULL, level_id INT NOT NULL, INDEX IDX_8B790DCB23EDC87 (subject_id), INDEX IDX_8B790DCB5FB14BA7 (level_id), PRIMARY KEY(subject_id, level_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE subject_document (subject_id INT NOT NULL, document_id INT NOT NULL, INDEX IDX_E014CA1E23EDC87 (subject_id), INDEX IDX_E014CA1EC33F7837 (document_id), PRIMARY KEY(subject_id, document_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE theme (id INT AUTO_INCREMENT NOT NULL, name VARCHAR(255) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE theme_subject (theme_id INT NOT NULL, subject_id INT NOT NULL, INDEX IDX_A2AD3B5959027487 (theme_id), INDEX IDX_A2AD3B5923EDC87 (subject_id), PRIMARY KEY(theme_id, subject_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE theme_document (theme_id INT NOT NULL, document_id INT NOT NULL, INDEX IDX_7A57980159027487 (theme_id), INDEX IDX_7A579801C33F7837 (document_id), PRIMARY KEY(theme_id, document_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE type (id INT AUTO_INCREMENT NOT NULL, name VARCHAR(255) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE document ADD CONSTRAINT FK_D8698A76C54C8C93 FOREIGN KEY (type_id) REFERENCES type (id)');
        $this->addSql('ALTER TABLE level_document ADD CONSTRAINT FK_55C02FA65FB14BA7 FOREIGN KEY (level_id) REFERENCES level (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE level_document ADD CONSTRAINT FK_55C02FA6C33F7837 FOREIGN KEY (document_id) REFERENCES document (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE subject_level ADD CONSTRAINT FK_8B790DCB23EDC87 FOREIGN KEY (subject_id) REFERENCES subject (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE subject_level ADD CONSTRAINT FK_8B790DCB5FB14BA7 FOREIGN KEY (level_id) REFERENCES level (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE subject_document ADD CONSTRAINT FK_E014CA1E23EDC87 FOREIGN KEY (subject_id) REFERENCES subject (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE subject_document ADD CONSTRAINT FK_E014CA1EC33F7837 FOREIGN KEY (document_id) REFERENCES document (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE theme_subject ADD CONSTRAINT FK_A2AD3B5959027487 FOREIGN KEY (theme_id) REFERENCES theme (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE theme_subject ADD CONSTRAINT FK_A2AD3B5923EDC87 FOREIGN KEY (subject_id) REFERENCES subject (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE theme_document ADD CONSTRAINT FK_7A57980159027487 FOREIGN KEY (theme_id) REFERENCES theme (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE theme_document ADD CONSTRAINT FK_7A579801C33F7837 FOREIGN KEY (document_id) REFERENCES document (id) ON DELETE CASCADE');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE document DROP FOREIGN KEY FK_D8698A76C54C8C93');
        $this->addSql('ALTER TABLE level_document DROP FOREIGN KEY FK_55C02FA65FB14BA7');
        $this->addSql('ALTER TABLE level_document DROP FOREIGN KEY FK_55C02FA6C33F7837');
        $this->addSql('ALTER TABLE subject_level DROP FOREIGN KEY FK_8B790DCB23EDC87');
        $this->addSql('ALTER TABLE subject_level DROP FOREIGN KEY FK_8B790DCB5FB14BA7');
        $this->addSql('ALTER TABLE subject_document DROP FOREIGN KEY FK_E014CA1E23EDC87');
        $this->addSql('ALTER TABLE subject_document DROP FOREIGN KEY FK_E014CA1EC33F7837');
        $this->addSql('ALTER TABLE theme_subject DROP FOREIGN KEY FK_A2AD3B5959027487');
        $this->addSql('ALTER TABLE theme_subject DROP FOREIGN KEY FK_A2AD3B5923EDC87');
        $this->addSql('ALTER TABLE theme_document DROP FOREIGN KEY FK_7A57980159027487');
        $this->addSql('ALTER TABLE theme_document DROP FOREIGN KEY FK_7A579801C33F7837');
        $this->addSql('DROP TABLE document');
        $this->addSql('DROP TABLE level');
        $this->addSql('DROP TABLE level_document');
        $this->addSql('DROP TABLE subject');
        $this->addSql('DROP TABLE subject_level');
        $this->addSql('DROP TABLE subject_document');
        $this->addSql('DROP TABLE theme');
        $this->addSql('DROP TABLE theme_subject');
        $this->addSql('DROP TABLE theme_document');
        $this->addSql('DROP TABLE type');
    }
}
