<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20210302192141 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('CREATE TABLE account_state (id INT AUTO_INCREMENT NOT NULL, name VARCHAR(255) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET UTF8 COLLATE `UTF8_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE analytics_account (id INT AUTO_INCREMENT NOT NULL, owner_id INT DEFAULT NULL, exchange_id INT DEFAULT NULL, APIkey VARCHAR(255) NOT NULL, APIsecret VARCHAR(255) NOT NULL, isActive TINYINT(1) NOT NULL, INDEX IDX_D794DA5A7E3C61F9 (owner_id), INDEX IDX_D794DA5A68AFD1A0 (exchange_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET UTF8 COLLATE `UTF8_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE account_to_instruments (account_id INT NOT NULL, instrument_id INT NOT NULL, INDEX IDX_9F3424629B6B5FBA (account_id), INDEX IDX_9F342462CF11D9C (instrument_id), PRIMARY KEY(account_id, instrument_id)) DEFAULT CHARACTER SET UTF8 COLLATE `UTF8_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE analytics_asset (id INT AUTO_INCREMENT NOT NULL, name VARCHAR(255) NOT NULL, fullName VARCHAR(255) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET UTF8 COLLATE `UTF8_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE analytics_capitalization (id INT AUTO_INCREMENT NOT NULL, asset_id INT DEFAULT NULL, createdDate DATETIME NOT NULL, value VARCHAR(255) NOT NULL, percent VARCHAR(255) NOT NULL, INDEX IDX_40BCD9D95DA1941 (asset_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET UTF8 COLLATE `UTF8_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE analytics_exchange (id INT AUTO_INCREMENT NOT NULL, name VARCHAR(255) NOT NULL, value VARCHAR(255) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET UTF8 COLLATE `UTF8_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE analytics_instrument (id INT AUTO_INCREMENT NOT NULL, exchange_id INT DEFAULT NULL, name VARCHAR(255) NOT NULL, value VARCHAR(255) NOT NULL, sort VARCHAR(255) DEFAULT NULL, round INT NOT NULL, lastPrice VARCHAR(255) DEFAULT NULL, lastClose VARCHAR(255) DEFAULT NULL, INDEX IDX_2C0B222E68AFD1A0 (exchange_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET UTF8 COLLATE `UTF8_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE analytics_account ADD CONSTRAINT FK_D794DA5A7E3C61F9 FOREIGN KEY (owner_id) REFERENCES accounts (id)');
        $this->addSql('ALTER TABLE analytics_account ADD CONSTRAINT FK_D794DA5A68AFD1A0 FOREIGN KEY (exchange_id) REFERENCES analytics_exchange (id)');
        $this->addSql('ALTER TABLE account_to_instruments ADD CONSTRAINT FK_9F3424629B6B5FBA FOREIGN KEY (account_id) REFERENCES analytics_account (id)');
        $this->addSql('ALTER TABLE account_to_instruments ADD CONSTRAINT FK_9F342462CF11D9C FOREIGN KEY (instrument_id) REFERENCES analytics_instrument (id)');
        $this->addSql('ALTER TABLE analytics_capitalization ADD CONSTRAINT FK_40BCD9D95DA1941 FOREIGN KEY (asset_id) REFERENCES analytics_asset (id)');
        $this->addSql('ALTER TABLE analytics_instrument ADD CONSTRAINT FK_2C0B222E68AFD1A0 FOREIGN KEY (exchange_id) REFERENCES analytics_exchange (id)');
        $this->addSql('ALTER TABLE accounts ADD account_state_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE accounts ADD CONSTRAINT FK_CAC89EAC7D16DE77 FOREIGN KEY (account_state_id) REFERENCES account_state (id)');
        $this->addSql('CREATE INDEX IDX_CAC89EAC7D16DE77 ON accounts (account_state_id)');
        $this->addSql('ALTER TABLE wallets ADD name VARCHAR(250) NOT NULL, ADD currency VARCHAR(50) DEFAULT NULL, ADD type VARCHAR(250) NOT NULL, ADD status VARCHAR(50) NOT NULL, ADD operations LONGTEXT NOT NULL COMMENT \'(DC2Type:array)\', DROP total_equity, DROP invested_amount, DROP qr, CHANGE account_id account_id VARCHAR(20) NOT NULL, CHANGE address address VARCHAR(250) NOT NULL');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE accounts DROP FOREIGN KEY FK_CAC89EAC7D16DE77');
        $this->addSql('ALTER TABLE account_to_instruments DROP FOREIGN KEY FK_9F3424629B6B5FBA');
        $this->addSql('ALTER TABLE analytics_capitalization DROP FOREIGN KEY FK_40BCD9D95DA1941');
        $this->addSql('ALTER TABLE analytics_account DROP FOREIGN KEY FK_D794DA5A68AFD1A0');
        $this->addSql('ALTER TABLE analytics_instrument DROP FOREIGN KEY FK_2C0B222E68AFD1A0');
        $this->addSql('ALTER TABLE account_to_instruments DROP FOREIGN KEY FK_9F342462CF11D9C');
        $this->addSql('DROP TABLE account_state');
        $this->addSql('DROP TABLE analytics_account');
        $this->addSql('DROP TABLE account_to_instruments');
        $this->addSql('DROP TABLE analytics_asset');
        $this->addSql('DROP TABLE analytics_capitalization');
        $this->addSql('DROP TABLE analytics_exchange');
        $this->addSql('DROP TABLE analytics_instrument');
        $this->addSql('DROP INDEX IDX_CAC89EAC7D16DE77 ON accounts');
        $this->addSql('ALTER TABLE accounts DROP account_state_id');
        $this->addSql('ALTER TABLE wallets ADD total_equity DOUBLE PRECISION DEFAULT NULL, ADD invested_amount DOUBLE PRECISION DEFAULT NULL, ADD qr VARCHAR(255) CHARACTER SET utf8 DEFAULT NULL COLLATE `utf8_unicode_ci`, DROP name, DROP currency, DROP type, DROP status, DROP operations, CHANGE account_id account_id INT NOT NULL, CHANGE address address VARCHAR(255) CHARACTER SET utf8 DEFAULT NULL COLLATE `utf8_unicode_ci`');
    }
}
