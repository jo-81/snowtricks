<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20230520124231 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE comment_signaled (id INT AUTO_INCREMENT NOT NULL, comment_id INT NOT NULL, created_at DATETIME NOT NULL COMMENT \'(DC2Type:datetime_immutable)\', reason VARCHAR(255) NOT NULL, edited_at DATETIME NOT NULL COMMENT \'(DC2Type:datetime_immutable)\', valided TINYINT(1) NOT NULL, UNIQUE INDEX UNIQ_E7664D65F8697D13 (comment_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE comment_signaled ADD CONSTRAINT FK_E7664D65F8697D13 FOREIGN KEY (comment_id) REFERENCES comment (id)');
        $this->addSql('ALTER TABLE comment DROP signaled, DROP blocked, DROP reason');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE comment_signaled DROP FOREIGN KEY FK_E7664D65F8697D13');
        $this->addSql('DROP TABLE comment_signaled');
        $this->addSql('ALTER TABLE comment ADD signaled TINYINT(1) NOT NULL, ADD blocked TINYINT(1) NOT NULL, ADD reason VARCHAR(255) DEFAULT NULL');
    }
}
