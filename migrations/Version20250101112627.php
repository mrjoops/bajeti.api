<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20250101112627 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE accounts (id INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL, budget_id INTEGER NOT NULL, name VARCHAR(255) NOT NULL, uuid BLOB NOT NULL --(DC2Type:uuid)
        , CONSTRAINT FK_CAC89EAC36ABA6B8 FOREIGN KEY (budget_id) REFERENCES budgets (id) NOT DEFERRABLE INITIALLY IMMEDIATE)');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_CAC89EACD17F50A6 ON accounts (uuid)');
        $this->addSql('CREATE INDEX IDX_CAC89EAC36ABA6B8 ON accounts (budget_id)');
        $this->addSql('CREATE TABLE budgets (id INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL, ends_at DATE NOT NULL --(DC2Type:date_immutable)
        , name VARCHAR(255) NOT NULL, starts_at DATE NOT NULL --(DC2Type:date_immutable)
        , uuid BLOB NOT NULL --(DC2Type:uuid)
        )');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_DCAA9548D17F50A6 ON budgets (uuid)');
        $this->addSql('CREATE TABLE categories (id INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL, name VARCHAR(255) NOT NULL, uuid BLOB NOT NULL --(DC2Type:uuid)
        )');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_3AF34668D17F50A6 ON categories (uuid)');
        $this->addSql('CREATE TABLE ext_translations (id INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL, locale VARCHAR(8) NOT NULL, object_class VARCHAR(191) NOT NULL, field VARCHAR(32) NOT NULL, foreign_key VARCHAR(64) NOT NULL, content CLOB DEFAULT NULL)');
        $this->addSql('CREATE INDEX translations_lookup_idx ON ext_translations (locale, object_class, foreign_key)');
        $this->addSql('CREATE INDEX general_translations_lookup_idx ON ext_translations (object_class, foreign_key)');
        $this->addSql('CREATE UNIQUE INDEX lookup_unique_idx ON ext_translations (locale, object_class, field, foreign_key)');
        $this->addSql('CREATE TABLE operations (id INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL, account_id INTEGER NOT NULL, category_id INTEGER DEFAULT NULL, party_id INTEGER DEFAULT NULL, amount NUMERIC(7, 2) NOT NULL, "date" DATE NOT NULL, name VARCHAR(255) NOT NULL, uuid BLOB NOT NULL --(DC2Type:uuid)
        , CONSTRAINT FK_281453489B6B5FBA FOREIGN KEY (account_id) REFERENCES accounts (id) NOT DEFERRABLE INITIALLY IMMEDIATE, CONSTRAINT FK_2814534812469DE2 FOREIGN KEY (category_id) REFERENCES categories (id) NOT DEFERRABLE INITIALLY IMMEDIATE, CONSTRAINT FK_28145348213C1059 FOREIGN KEY (party_id) REFERENCES taxons (id) NOT DEFERRABLE INITIALLY IMMEDIATE)');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_28145348D17F50A6 ON operations (uuid)');
        $this->addSql('CREATE INDEX IDX_281453489B6B5FBA ON operations (account_id)');
        $this->addSql('CREATE INDEX IDX_2814534812469DE2 ON operations (category_id)');
        $this->addSql('CREATE INDEX IDX_28145348213C1059 ON operations (party_id)');
        $this->addSql('CREATE TABLE operations_tags (operation_id INTEGER NOT NULL, tag_id INTEGER NOT NULL, PRIMARY KEY(operation_id, tag_id), CONSTRAINT FK_C5D0F2FF44AC3583 FOREIGN KEY (operation_id) REFERENCES operations (id) ON DELETE CASCADE NOT DEFERRABLE INITIALLY IMMEDIATE, CONSTRAINT FK_C5D0F2FFBAD26311 FOREIGN KEY (tag_id) REFERENCES taxons (id) ON DELETE CASCADE NOT DEFERRABLE INITIALLY IMMEDIATE)');
        $this->addSql('CREATE INDEX IDX_C5D0F2FF44AC3583 ON operations_tags (operation_id)');
        $this->addSql('CREATE INDEX IDX_C5D0F2FFBAD26311 ON operations_tags (tag_id)');
        $this->addSql('CREATE TABLE taxons (id INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL, name VARCHAR(255) NOT NULL, uuid BLOB NOT NULL --(DC2Type:uuid)
        , discr SMALLINT NOT NULL)');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_5A51D2485E237E06 ON taxons (name)');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_5A51D248D17F50A6 ON taxons (uuid)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('DROP TABLE accounts');
        $this->addSql('DROP TABLE budgets');
        $this->addSql('DROP TABLE categories');
        $this->addSql('DROP TABLE ext_translations');
        $this->addSql('DROP TABLE operations');
        $this->addSql('DROP TABLE operations_tags');
        $this->addSql('DROP TABLE taxons');
    }
}
