<?php

namespace Application\Migrations;

use Doctrine\DBAL\Migrations\AbstractMigration;
use Doctrine\DBAL\Schema\Schema;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
class Version20151117113304 extends AbstractMigration
{
    /**
     * @param Schema $schema
     */
    public function up(Schema $schema)
    {
        // this up() migration is auto-generated, please modify it to your needs
        $sql[] = <<<SQL
CREATE TABLE IF NOT EXISTS "dns_user" (
    "id"             INTEGER      NOT NULL PRIMARY KEY AUTOINCREMENT,
    "user_name"      TEXT         NOT NULL UNIQUE,
    "password"       TEXT         NOT NULL,
    "control_no"     TEXT,
    "comment"        TEXT,
    "encrypt_type"   INTEGER      NOT NULL DEFAULT 1,
    "created_at"     TEXT         NOT NULL DEFAULT CURRENT_TIMESTAMP,
    "updated_at"     TEXT
);
SQL;

        $sql[] = <<<SQL
CREATE INDEX "idx_dns_user_control_no" on "dns_user" ("control_no");
SQL;

        $sql[] = <<<SQL
CREATE INDEX "idx_dns_user_comment" on "dns_user" ("comment");
SQL;

        $sql[] = <<<SQL
CREATE INDEX "idx_dns_user_created_at" on "dns_user" ("created_at");
SQL;

        // データの登録
        $sql[] = <<<SQL
INSERT INTO "dns_user" ("user_name", "password", "control_no", "comment", "encrypt_type") VALUES
("test001", "test001", "test001", "TEST検証機", 1),
SQL;

        $this->addSql($sql);
    }

    /**
     * @param Schema $schema
     */
    public function down(Schema $schema)
    {
        // this down() migration is auto-generated, please modify it to your needs
        $sql[] = 'DROP TABLE IF EXISTS "dns_user";';
        $this->addSql($sql);
    }
}
