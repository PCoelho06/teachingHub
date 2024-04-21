<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20240421145556 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE comment (id INT AUTO_INCREMENT NOT NULL, author_id INT DEFAULT NULL, document_id INT NOT NULL, created_at DATETIME NOT NULL COMMENT \'(DC2Type:datetime_immutable)\', edited_at DATETIME DEFAULT NULL COMMENT \'(DC2Type:datetime_immutable)\', content LONGTEXT NOT NULL, rating INT NOT NULL, INDEX IDX_9474526CF675F31B (author_id), INDEX IDX_9474526CC33F7837 (document_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE document (id INT AUTO_INCREMENT NOT NULL, type_id INT NOT NULL, author_id INT NOT NULL, title VARCHAR(255) NOT NULL, description LONGTEXT NOT NULL, file VARCHAR(255) NOT NULL, rating_average DOUBLE PRECISION DEFAULT NULL, uploaded_at DATETIME NOT NULL COMMENT \'(DC2Type:datetime_immutable)\', updated_at DATETIME DEFAULT NULL COMMENT \'(DC2Type:datetime_immutable)\', slug VARCHAR(255) NOT NULL, downloads_number INT NOT NULL, INDEX IDX_D8698A76C54C8C93 (type_id), INDEX IDX_D8698A76F675F31B (author_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE document_level (document_id INT NOT NULL, level_id INT NOT NULL, INDEX IDX_F34BBA61C33F7837 (document_id), INDEX IDX_F34BBA615FB14BA7 (level_id), PRIMARY KEY(document_id, level_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE document_subject (document_id INT NOT NULL, subject_id INT NOT NULL, INDEX IDX_5FA198A6C33F7837 (document_id), INDEX IDX_5FA198A623EDC87 (subject_id), PRIMARY KEY(document_id, subject_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE document_theme (document_id INT NOT NULL, theme_id INT NOT NULL, INDEX IDX_FED4917AC33F7837 (document_id), INDEX IDX_FED4917A59027487 (theme_id), PRIMARY KEY(document_id, theme_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE document_user (document_id INT NOT NULL, user_id INT NOT NULL, INDEX IDX_2A275ADAC33F7837 (document_id), INDEX IDX_2A275ADAA76ED395 (user_id), PRIMARY KEY(document_id, user_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE level (id INT AUTO_INCREMENT NOT NULL, name VARCHAR(255) NOT NULL, short VARCHAR(255) DEFAULT NULL, color VARCHAR(255) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE level_theme (level_id INT NOT NULL, theme_id INT NOT NULL, INDEX IDX_FDACE2015FB14BA7 (level_id), INDEX IDX_FDACE20159027487 (theme_id), PRIMARY KEY(level_id, theme_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE reset_password_request (id INT AUTO_INCREMENT NOT NULL, user_id INT NOT NULL, selector VARCHAR(20) NOT NULL, hashed_token VARCHAR(100) NOT NULL, requested_at DATETIME NOT NULL COMMENT \'(DC2Type:datetime_immutable)\', expires_at DATETIME NOT NULL COMMENT \'(DC2Type:datetime_immutable)\', INDEX IDX_7CE748AA76ED395 (user_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE subject (id INT AUTO_INCREMENT NOT NULL, name VARCHAR(255) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE subject_level (subject_id INT NOT NULL, level_id INT NOT NULL, INDEX IDX_8B790DCB23EDC87 (subject_id), INDEX IDX_8B790DCB5FB14BA7 (level_id), PRIMARY KEY(subject_id, level_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE theme (id INT AUTO_INCREMENT NOT NULL, name VARCHAR(255) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE theme_subject (theme_id INT NOT NULL, subject_id INT NOT NULL, INDEX IDX_A2AD3B5959027487 (theme_id), INDEX IDX_A2AD3B5923EDC87 (subject_id), PRIMARY KEY(theme_id, subject_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE type (id INT AUTO_INCREMENT NOT NULL, name VARCHAR(255) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE user (id INT AUTO_INCREMENT NOT NULL, email VARCHAR(180) NOT NULL, roles JSON NOT NULL, password VARCHAR(255) NOT NULL, firstname VARCHAR(255) DEFAULT NULL, lastname VARCHAR(255) DEFAULT NULL, username VARCHAR(255) NOT NULL, registered_at DATETIME NOT NULL COMMENT \'(DC2Type:datetime_immutable)\', is_verified TINYINT(1) NOT NULL, UNIQUE INDEX UNIQ_8D93D649E7927C74 (email), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE user_document (user_id INT NOT NULL, document_id INT NOT NULL, INDEX IDX_38E46E76A76ED395 (user_id), INDEX IDX_38E46E76C33F7837 (document_id), PRIMARY KEY(user_id, document_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE messenger_messages (id BIGINT AUTO_INCREMENT NOT NULL, body LONGTEXT NOT NULL, headers LONGTEXT NOT NULL, queue_name VARCHAR(190) NOT NULL, created_at DATETIME NOT NULL COMMENT \'(DC2Type:datetime_immutable)\', available_at DATETIME NOT NULL COMMENT \'(DC2Type:datetime_immutable)\', delivered_at DATETIME DEFAULT NULL COMMENT \'(DC2Type:datetime_immutable)\', INDEX IDX_75EA56E0FB7336F0 (queue_name), INDEX IDX_75EA56E0E3BD61CE (available_at), INDEX IDX_75EA56E016BA31DB (delivered_at), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE comment ADD CONSTRAINT FK_9474526CF675F31B FOREIGN KEY (author_id) REFERENCES user (id)');
        $this->addSql('ALTER TABLE comment ADD CONSTRAINT FK_9474526CC33F7837 FOREIGN KEY (document_id) REFERENCES document (id)');
        $this->addSql('ALTER TABLE document ADD CONSTRAINT FK_D8698A76C54C8C93 FOREIGN KEY (type_id) REFERENCES type (id)');
        $this->addSql('ALTER TABLE document ADD CONSTRAINT FK_D8698A76F675F31B FOREIGN KEY (author_id) REFERENCES user (id)');
        $this->addSql('ALTER TABLE document_level ADD CONSTRAINT FK_F34BBA61C33F7837 FOREIGN KEY (document_id) REFERENCES document (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE document_level ADD CONSTRAINT FK_F34BBA615FB14BA7 FOREIGN KEY (level_id) REFERENCES level (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE document_subject ADD CONSTRAINT FK_5FA198A6C33F7837 FOREIGN KEY (document_id) REFERENCES document (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE document_subject ADD CONSTRAINT FK_5FA198A623EDC87 FOREIGN KEY (subject_id) REFERENCES subject (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE document_theme ADD CONSTRAINT FK_FED4917AC33F7837 FOREIGN KEY (document_id) REFERENCES document (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE document_theme ADD CONSTRAINT FK_FED4917A59027487 FOREIGN KEY (theme_id) REFERENCES theme (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE document_user ADD CONSTRAINT FK_2A275ADAC33F7837 FOREIGN KEY (document_id) REFERENCES document (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE document_user ADD CONSTRAINT FK_2A275ADAA76ED395 FOREIGN KEY (user_id) REFERENCES user (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE level_theme ADD CONSTRAINT FK_FDACE2015FB14BA7 FOREIGN KEY (level_id) REFERENCES level (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE level_theme ADD CONSTRAINT FK_FDACE20159027487 FOREIGN KEY (theme_id) REFERENCES theme (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE reset_password_request ADD CONSTRAINT FK_7CE748AA76ED395 FOREIGN KEY (user_id) REFERENCES user (id)');
        $this->addSql('ALTER TABLE subject_level ADD CONSTRAINT FK_8B790DCB23EDC87 FOREIGN KEY (subject_id) REFERENCES subject (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE subject_level ADD CONSTRAINT FK_8B790DCB5FB14BA7 FOREIGN KEY (level_id) REFERENCES level (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE theme_subject ADD CONSTRAINT FK_A2AD3B5959027487 FOREIGN KEY (theme_id) REFERENCES theme (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE theme_subject ADD CONSTRAINT FK_A2AD3B5923EDC87 FOREIGN KEY (subject_id) REFERENCES subject (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE user_document ADD CONSTRAINT FK_38E46E76A76ED395 FOREIGN KEY (user_id) REFERENCES user (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE user_document ADD CONSTRAINT FK_38E46E76C33F7837 FOREIGN KEY (document_id) REFERENCES document (id) ON DELETE CASCADE');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE comment DROP FOREIGN KEY FK_9474526CF675F31B');
        $this->addSql('ALTER TABLE comment DROP FOREIGN KEY FK_9474526CC33F7837');
        $this->addSql('ALTER TABLE document DROP FOREIGN KEY FK_D8698A76C54C8C93');
        $this->addSql('ALTER TABLE document DROP FOREIGN KEY FK_D8698A76F675F31B');
        $this->addSql('ALTER TABLE document_level DROP FOREIGN KEY FK_F34BBA61C33F7837');
        $this->addSql('ALTER TABLE document_level DROP FOREIGN KEY FK_F34BBA615FB14BA7');
        $this->addSql('ALTER TABLE document_subject DROP FOREIGN KEY FK_5FA198A6C33F7837');
        $this->addSql('ALTER TABLE document_subject DROP FOREIGN KEY FK_5FA198A623EDC87');
        $this->addSql('ALTER TABLE document_theme DROP FOREIGN KEY FK_FED4917AC33F7837');
        $this->addSql('ALTER TABLE document_theme DROP FOREIGN KEY FK_FED4917A59027487');
        $this->addSql('ALTER TABLE document_user DROP FOREIGN KEY FK_2A275ADAC33F7837');
        $this->addSql('ALTER TABLE document_user DROP FOREIGN KEY FK_2A275ADAA76ED395');
        $this->addSql('ALTER TABLE level_theme DROP FOREIGN KEY FK_FDACE2015FB14BA7');
        $this->addSql('ALTER TABLE level_theme DROP FOREIGN KEY FK_FDACE20159027487');
        $this->addSql('ALTER TABLE reset_password_request DROP FOREIGN KEY FK_7CE748AA76ED395');
        $this->addSql('ALTER TABLE subject_level DROP FOREIGN KEY FK_8B790DCB23EDC87');
        $this->addSql('ALTER TABLE subject_level DROP FOREIGN KEY FK_8B790DCB5FB14BA7');
        $this->addSql('ALTER TABLE theme_subject DROP FOREIGN KEY FK_A2AD3B5959027487');
        $this->addSql('ALTER TABLE theme_subject DROP FOREIGN KEY FK_A2AD3B5923EDC87');
        $this->addSql('ALTER TABLE user_document DROP FOREIGN KEY FK_38E46E76A76ED395');
        $this->addSql('ALTER TABLE user_document DROP FOREIGN KEY FK_38E46E76C33F7837');
        $this->addSql('DROP TABLE comment');
        $this->addSql('DROP TABLE document');
        $this->addSql('DROP TABLE document_level');
        $this->addSql('DROP TABLE document_subject');
        $this->addSql('DROP TABLE document_theme');
        $this->addSql('DROP TABLE document_user');
        $this->addSql('DROP TABLE level');
        $this->addSql('DROP TABLE level_theme');
        $this->addSql('DROP TABLE reset_password_request');
        $this->addSql('DROP TABLE subject');
        $this->addSql('DROP TABLE subject_level');
        $this->addSql('DROP TABLE theme');
        $this->addSql('DROP TABLE theme_subject');
        $this->addSql('DROP TABLE type');
        $this->addSql('DROP TABLE user');
        $this->addSql('DROP TABLE user_document');
        $this->addSql('DROP TABLE messenger_messages');
    }
}
