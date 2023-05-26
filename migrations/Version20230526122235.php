<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20230526122235 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE comment_signaled DROP FOREIGN KEY FK_E7664D65F8697D13');
        $this->addSql('ALTER TABLE comment_signaled CHANGE comment_id comment_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE comment_signaled ADD CONSTRAINT FK_E7664D65F8697D13 FOREIGN KEY (comment_id) REFERENCES comment (id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE comment_signaled DROP FOREIGN KEY FK_E7664D65F8697D13');
        $this->addSql('ALTER TABLE comment_signaled CHANGE comment_id comment_id INT NOT NULL');
        $this->addSql('ALTER TABLE comment_signaled ADD CONSTRAINT FK_E7664D65F8697D13 FOREIGN KEY (comment_id) REFERENCES comment (id) ON DELETE CASCADE');
    }
}
