<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20200511144019 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('CREATE TABLE todo_category (id INT AUTO_INCREMENT NOT NULL, name VARCHAR(255) NOT NULL, created_at DATETIME NOT NULL, edited_at DATETIME DEFAULT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE todo CHANGE description description VARCHAR(4096) DEFAULT NULL, CHANGE due_date due_date DATETIME DEFAULT NULL, CHANGE completion_date completion_date DATETIME DEFAULT NULL');
        $this->addSql('ALTER TABLE user CHANGE last_login_time last_login_time DATETIME DEFAULT NULL');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('DROP TABLE todo_category');
        $this->addSql('ALTER TABLE todo CHANGE description description VARCHAR(4096) CHARACTER SET utf8mb4 DEFAULT \'NULL\' COLLATE `utf8mb4_unicode_ci`, CHANGE due_date due_date DATETIME DEFAULT \'NULL\', CHANGE completion_date completion_date DATETIME DEFAULT \'NULL\'');
        $this->addSql('ALTER TABLE user CHANGE last_login_time last_login_time DATETIME DEFAULT \'NULL\'');
    }
}
