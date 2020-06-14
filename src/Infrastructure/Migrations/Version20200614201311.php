<?php

declare(strict_types=1);

namespace App\Infrastructure\Migrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20200614201311 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'sqlite', 'Migration can only be executed safely on \'sqlite\'.');

        $this->addSql('CREATE TABLE task (id INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL, creator_id INTEGER DEFAULT NULL, required_workers INTEGER NOT NULL, start_date DATETIME NOT NULL, end_date DATETIME NOT NULL)');
        $this->addSql('CREATE INDEX IDX_527EDB2561220EA6 ON task (creator_id)');
        $this->addSql('CREATE TABLE task_user (task_id INTEGER NOT NULL, user_id INTEGER NOT NULL, PRIMARY KEY(task_id, user_id))');
        $this->addSql('CREATE INDEX IDX_FE2042328DB60186 ON task_user (task_id)');
        $this->addSql('CREATE INDEX IDX_FE204232A76ED395 ON task_user (user_id)');
        $this->addSql('CREATE TABLE user (id INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL, name VARCHAR(255) NOT NULL, given_name VARCHAR(255) NOT NULL, family_name VARCHAR(255) NOT NULL, email VARCHAR(255) NOT NULL, google_id VARCHAR(255) NOT NULL, roles CLOB NOT NULL --(DC2Type:json)
        , picture VARCHAR(255) NOT NULL)');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_8D93D649E7927C74 ON user (email)');
        $this->addSql('CREATE TABLE goal (id INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL, task_id INTEGER DEFAULT NULL, goal_identifier VARCHAR(36) NOT NULL, name VARCHAR(255) NOT NULL, is_finished BOOLEAN NOT NULL, realization_description CLOB NOT NULL)');
        $this->addSql('CREATE INDEX IDX_FCDCEB2E8DB60186 ON goal (task_id)');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'sqlite', 'Migration can only be executed safely on \'sqlite\'.');

        $this->addSql('DROP TABLE task');
        $this->addSql('DROP TABLE task_user');
        $this->addSql('DROP TABLE user');
        $this->addSql('DROP TABLE goal');
    }
}
