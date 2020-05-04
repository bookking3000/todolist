<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20200427134213 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('CREATE TABLE todo (id INT AUTO_INCREMENT NOT NULL, owner_id INT NOT NULL, name VARCHAR(255) NOT NULL, description VARCHAR(4096) DEFAULT NULL, creation_date DATETIME NOT NULL, due_date DATETIME DEFAULT NULL, INDEX IDX_5A0EB6A07E3C61F9 (owner_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE todo_user (todo_id INT NOT NULL, user_id INT NOT NULL, INDEX IDX_D242B056EA1EBC33 (todo_id), INDEX IDX_D242B056A76ED395 (user_id), PRIMARY KEY(todo_id, user_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE user (id INT AUTO_INCREMENT NOT NULL, is_validated TINYINT(1) NOT NULL, username VARCHAR(255) NOT NULL, email VARCHAR(255) NOT NULL, registration_date DATETIME NOT NULL, last_login_time DATETIME DEFAULT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE todo ADD CONSTRAINT FK_5A0EB6A07E3C61F9 FOREIGN KEY (owner_id) REFERENCES user (id)');
        $this->addSql('ALTER TABLE todo_user ADD CONSTRAINT FK_D242B056EA1EBC33 FOREIGN KEY (todo_id) REFERENCES todo (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE todo_user ADD CONSTRAINT FK_D242B056A76ED395 FOREIGN KEY (user_id) REFERENCES user (id) ON DELETE CASCADE');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE todo_user DROP FOREIGN KEY FK_D242B056EA1EBC33');
        $this->addSql('ALTER TABLE todo DROP FOREIGN KEY FK_5A0EB6A07E3C61F9');
        $this->addSql('ALTER TABLE todo_user DROP FOREIGN KEY FK_D242B056A76ED395');
        $this->addSql('DROP TABLE todo');
        $this->addSql('DROP TABLE todo_user');
        $this->addSql('DROP TABLE user');
    }
}
