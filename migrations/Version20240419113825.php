<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20240419113825 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE theme_level DROP FOREIGN KEY FK_C2C847F659027487');
        $this->addSql('ALTER TABLE theme_level DROP FOREIGN KEY FK_C2C847F65FB14BA7');
        $this->addSql('DROP TABLE theme_level');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE theme_level (theme_id INT NOT NULL, level_id INT NOT NULL, INDEX IDX_C2C847F659027487 (theme_id), INDEX IDX_C2C847F65FB14BA7 (level_id), PRIMARY KEY(theme_id, level_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB COMMENT = \'\' ');
        $this->addSql('ALTER TABLE theme_level ADD CONSTRAINT FK_C2C847F659027487 FOREIGN KEY (theme_id) REFERENCES theme (id) ON UPDATE NO ACTION ON DELETE CASCADE');
        $this->addSql('ALTER TABLE theme_level ADD CONSTRAINT FK_C2C847F65FB14BA7 FOREIGN KEY (level_id) REFERENCES level (id) ON UPDATE NO ACTION ON DELETE CASCADE');
    }
}
