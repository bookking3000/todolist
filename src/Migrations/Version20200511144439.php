<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20200511144439 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('CREATE TABLE todo_todo_category (todo_id INT NOT NULL, todo_category_id INT NOT NULL, INDEX IDX_B40AC7EEEA1EBC33 (todo_id), INDEX IDX_B40AC7EE7A86D49F (todo_category_id), PRIMARY KEY(todo_id, todo_category_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE todo_todo_category ADD CONSTRAINT FK_B40AC7EEEA1EBC33 FOREIGN KEY (todo_id) REFERENCES todo (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE todo_todo_category ADD CONSTRAINT FK_B40AC7EE7A86D49F FOREIGN KEY (todo_category_id) REFERENCES todo_category (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE todo CHANGE description description VARCHAR(4096) DEFAULT NULL, CHANGE due_date due_date DATETIME DEFAULT NULL, CHANGE completion_date completion_date DATETIME DEFAULT NULL');
        $this->addSql('ALTER TABLE todo_category ADD user_id INT DEFAULT NULL, CHANGE edited_at edited_at DATETIME DEFAULT NULL');
        $this->addSql('ALTER TABLE todo_category ADD CONSTRAINT FK_219B51A1A76ED395 FOREIGN KEY (user_id) REFERENCES user (id)');
        $this->addSql('CREATE INDEX IDX_219B51A1A76ED395 ON todo_category (user_id)');
        $this->addSql('ALTER TABLE user CHANGE last_login_time last_login_time DATETIME DEFAULT NULL');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('DROP TABLE todo_todo_category');
        $this->addSql('ALTER TABLE todo CHANGE description description VARCHAR(4096) CHARACTER SET utf8mb4 DEFAULT \'NULL\' COLLATE `utf8mb4_unicode_ci`, CHANGE due_date due_date DATETIME DEFAULT \'NULL\', CHANGE completion_date completion_date DATETIME DEFAULT \'NULL\'');
        $this->addSql('ALTER TABLE todo_category DROP FOREIGN KEY FK_219B51A1A76ED395');
        $this->addSql('DROP INDEX IDX_219B51A1A76ED395 ON todo_category');
        $this->addSql('ALTER TABLE todo_category DROP user_id, CHANGE edited_at edited_at DATETIME DEFAULT \'NULL\'');
        $this->addSql('ALTER TABLE user CHANGE last_login_time last_login_time DATETIME DEFAULT \'NULL\'');
    }
}
