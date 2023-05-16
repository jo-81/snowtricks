<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20230516135519 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE blocked DROP FOREIGN KEY FK_DA55EB80217BBB47');
        $this->addSql('ALTER TABLE blocked ADD CONSTRAINT FK_DA55EB80217BBB47 FOREIGN KEY (person_id) REFERENCES `user` (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE reset_password DROP FOREIGN KEY FK_B9983CE5217BBB47');
        $this->addSql('ALTER TABLE reset_password ADD CONSTRAINT FK_B9983CE5217BBB47 FOREIGN KEY (person_id) REFERENCES `user` (id) ON DELETE CASCADE');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE blocked DROP FOREIGN KEY FK_DA55EB80217BBB47');
        $this->addSql('ALTER TABLE blocked ADD CONSTRAINT FK_DA55EB80217BBB47 FOREIGN KEY (person_id) REFERENCES user (id)');
        $this->addSql('ALTER TABLE reset_password DROP FOREIGN KEY FK_B9983CE5217BBB47');
        $this->addSql('ALTER TABLE reset_password ADD CONSTRAINT FK_B9983CE5217BBB47 FOREIGN KEY (person_id) REFERENCES user (id)');
    }
}
