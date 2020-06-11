<?php

declare(strict_types=1);

namespace App\Infrastructure\Migrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20200611201109 extends AbstractMigration
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
        $this->addSql('CREATE UNIQUE INDEX UNIQ_527EDB2561220EA6 ON task (creator_id)');
        $this->addSql('CREATE TABLE goal (id INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL, task_id INTEGER DEFAULT NULL, goal_identifier VARCHAR(36) NOT NULL, name VARCHAR(255) NOT NULL, is_finished BOOLEAN NOT NULL, realization_description CLOB NOT NULL)');
        $this->addSql('CREATE INDEX IDX_FCDCEB2E8DB60186 ON goal (task_id)');
        $this->addSql('DROP INDEX UNIQ_8D93D649E7927C74');
        $this->addSql('CREATE TEMPORARY TABLE __temp__user AS SELECT id, name, given_name, family_name, email, google_id, roles, picture FROM user');
        $this->addSql('DROP TABLE user');
        $this->addSql('CREATE TABLE user (id INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL, worker_id INTEGER DEFAULT NULL, name VARCHAR(255) NOT NULL COLLATE BINARY, given_name VARCHAR(255) NOT NULL COLLATE BINARY, family_name VARCHAR(255) NOT NULL COLLATE BINARY, email VARCHAR(255) NOT NULL COLLATE BINARY, google_id VARCHAR(255) NOT NULL COLLATE BINARY, roles CLOB NOT NULL COLLATE BINARY --(DC2Type:json)
        , picture VARCHAR(255) NOT NULL COLLATE BINARY, CONSTRAINT FK_8D93D6496B20BA36 FOREIGN KEY (worker_id) REFERENCES task (id) NOT DEFERRABLE INITIALLY IMMEDIATE)');
        $this->addSql('INSERT INTO user (id, name, given_name, family_name, email, google_id, roles, picture) SELECT id, name, given_name, family_name, email, google_id, roles, picture FROM __temp__user');
        $this->addSql('DROP TABLE __temp__user');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_8D93D649E7927C74 ON user (email)');
        $this->addSql('CREATE INDEX IDX_8D93D6496B20BA36 ON user (worker_id)');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'sqlite', 'Migration can only be executed safely on \'sqlite\'.');

        $this->addSql('DROP TABLE task');
        $this->addSql('DROP TABLE goal');
        $this->addSql('DROP INDEX UNIQ_8D93D649E7927C74');
        $this->addSql('DROP INDEX IDX_8D93D6496B20BA36');
        $this->addSql('CREATE TEMPORARY TABLE __temp__user AS SELECT id, name, given_name, family_name, email, google_id, roles, picture FROM user');
        $this->addSql('DROP TABLE user');
        $this->addSql('CREATE TABLE user (id INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL, name VARCHAR(255) NOT NULL, given_name VARCHAR(255) NOT NULL, family_name VARCHAR(255) NOT NULL, email VARCHAR(255) NOT NULL, google_id VARCHAR(255) NOT NULL, roles CLOB NOT NULL --(DC2Type:json)
        , picture VARCHAR(255) NOT NULL)');
        $this->addSql('INSERT INTO user (id, name, given_name, family_name, email, google_id, roles, picture) SELECT id, name, given_name, family_name, email, google_id, roles, picture FROM __temp__user');
        $this->addSql('DROP TABLE __temp__user');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_8D93D649E7927C74 ON user (email)');
    }
}
