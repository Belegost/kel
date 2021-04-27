<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20210427191012 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE account_state (id INT AUTO_INCREMENT NOT NULL, name VARCHAR(255) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET UTF8 COLLATE `UTF8_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE accounts (id INT AUTO_INCREMENT NOT NULL, account_state_id INT DEFAULT NULL, public_key LONGTEXT NOT NULL, first_name VARCHAR(255) DEFAULT NULL, last_name VARCHAR(255) DEFAULT NULL, username VARCHAR(255) NOT NULL, password VARCHAR(255) NOT NULL, email VARCHAR(255) NOT NULL, avatar VARCHAR(255) DEFAULT NULL, zoho_contact_id VARCHAR(50) DEFAULT NULL, last_activity DATETIME DEFAULT NULL, rates_in_usd TINYINT(1) DEFAULT NULL, phone_number VARCHAR(50) NOT NULL, keep_signed_key VARCHAR(250) DEFAULT NULL, binance_sub_account_id VARCHAR(20) DEFAULT NULL, binance_api_key VARCHAR(64) DEFAULT NULL, binance_api_secret VARCHAR(64) DEFAULT NULL, UNIQUE INDEX UNIQ_CAC89EACF85E0677 (username), UNIQUE INDEX UNIQ_CAC89EACE7927C74 (email), UNIQUE INDEX UNIQ_CAC89EAC6B01BC5B (phone_number), INDEX IDX_CAC89EAC7D16DE77 (account_state_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET UTF8 COLLATE `UTF8_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE accounts_settings (id INT AUTO_INCREMENT NOT NULL, `account_id` INT NOT NULL, `setting_id` INT NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET UTF8 COLLATE `UTF8_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE analytics_account (id INT AUTO_INCREMENT NOT NULL, owner_id INT DEFAULT NULL, exchange_id INT DEFAULT NULL, APIkey VARCHAR(255) NOT NULL, APIsecret VARCHAR(255) NOT NULL, isActive TINYINT(1) NOT NULL, INDEX IDX_D794DA5A7E3C61F9 (owner_id), INDEX IDX_D794DA5A68AFD1A0 (exchange_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET UTF8 COLLATE `UTF8_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE account_to_instruments (account_id INT NOT NULL, instrument_id INT NOT NULL, INDEX IDX_9F3424629B6B5FBA (account_id), INDEX IDX_9F342462CF11D9C (instrument_id), PRIMARY KEY(account_id, instrument_id)) DEFAULT CHARACTER SET UTF8 COLLATE `UTF8_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE analytics_asset (id INT AUTO_INCREMENT NOT NULL, name VARCHAR(255) NOT NULL, fullName VARCHAR(255) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET UTF8 COLLATE `UTF8_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE analytics_capitalization (id INT AUTO_INCREMENT NOT NULL, asset_id INT DEFAULT NULL, createdDate DATETIME NOT NULL, value VARCHAR(255) NOT NULL, percent VARCHAR(255) NOT NULL, INDEX IDX_40BCD9D95DA1941 (asset_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET UTF8 COLLATE `UTF8_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE analytics_exchange (id INT AUTO_INCREMENT NOT NULL, name VARCHAR(255) NOT NULL, value VARCHAR(255) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET UTF8 COLLATE `UTF8_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE analytics_instrument (id INT AUTO_INCREMENT NOT NULL, exchange_id INT DEFAULT NULL, name VARCHAR(255) NOT NULL, value VARCHAR(255) NOT NULL, sort VARCHAR(255) DEFAULT NULL, round INT NOT NULL, lastPrice VARCHAR(255) DEFAULT NULL, lastClose VARCHAR(255) DEFAULT NULL, INDEX IDX_2C0B222E68AFD1A0 (exchange_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET UTF8 COLLATE `UTF8_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE news (id INT AUTO_INCREMENT NOT NULL, title VARCHAR(255) NOT NULL, description LONGTEXT NOT NULL, publicationDate DATETIME NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET UTF8 COLLATE `UTF8_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE setting_option_types (id SMALLINT NOT NULL, `name` VARCHAR(64) NOT NULL, UNIQUE INDEX UNIQ_7857DB5E999517A (`name`), PRIMARY KEY(id)) DEFAULT CHARACTER SET UTF8 COLLATE `UTF8_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE setting_options (id SMALLINT NOT NULL, `type_id` SMALLINT NOT NULL, `name` VARCHAR(128) NOT NULL, UNIQUE INDEX UNIQ_88674A6F999517A (`name`), PRIMARY KEY(id)) DEFAULT CHARACTER SET UTF8 COLLATE `UTF8_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE settings (id INT AUTO_INCREMENT NOT NULL, `option_id` SMALLINT NOT NULL, `value` LONGTEXT NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET UTF8 COLLATE `UTF8_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE sum_sub_reviews (id INT AUTO_INCREMENT NOT NULL, account_id INT NOT NULL, applicantId VARCHAR(50) NOT NULL, inspectionId VARCHAR(50) NOT NULL, corellationId VARCHAR(100) NOT NULL, reviewResult VARCHAR(10) NOT NULL, moderationComment LONGTEXT DEFAULT NULL, clientComment LONGTEXT DEFAULT NULL, rejectLabels JSON DEFAULT NULL, status VARCHAR(30) NOT NULL, createdAt DATETIME NOT NULL, INDEX IDX_D42117509B6B5FBA (account_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET UTF8 COLLATE `UTF8_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE tokens (id INT AUTO_INCREMENT NOT NULL, hash VARCHAR(64) NOT NULL, data JSON DEFAULT NULL COMMENT \'(DC2Type:json_array)\', expired_time DATETIME DEFAULT NULL, created_time DATETIME NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET UTF8 COLLATE `UTF8_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE wallets (id INT AUTO_INCREMENT NOT NULL, account_id VARCHAR(20) NOT NULL, name VARCHAR(250) NOT NULL, currency VARCHAR(50) DEFAULT NULL, type VARCHAR(250) NOT NULL, address VARCHAR(250) NOT NULL, status VARCHAR(50) NOT NULL, operations LONGTEXT NOT NULL COMMENT \'(DC2Type:array)\', PRIMARY KEY(id)) DEFAULT CHARACTER SET UTF8 COLLATE `UTF8_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE accounts ADD CONSTRAINT FK_CAC89EAC7D16DE77 FOREIGN KEY (account_state_id) REFERENCES account_state (id)');
        $this->addSql('ALTER TABLE analytics_account ADD CONSTRAINT FK_D794DA5A7E3C61F9 FOREIGN KEY (owner_id) REFERENCES accounts (id)');
        $this->addSql('ALTER TABLE analytics_account ADD CONSTRAINT FK_D794DA5A68AFD1A0 FOREIGN KEY (exchange_id) REFERENCES analytics_exchange (id)');
        $this->addSql('ALTER TABLE account_to_instruments ADD CONSTRAINT FK_9F3424629B6B5FBA FOREIGN KEY (account_id) REFERENCES analytics_account (id)');
        $this->addSql('ALTER TABLE account_to_instruments ADD CONSTRAINT FK_9F342462CF11D9C FOREIGN KEY (instrument_id) REFERENCES analytics_instrument (id)');
        $this->addSql('ALTER TABLE analytics_capitalization ADD CONSTRAINT FK_40BCD9D95DA1941 FOREIGN KEY (asset_id) REFERENCES analytics_asset (id)');
        $this->addSql('ALTER TABLE analytics_instrument ADD CONSTRAINT FK_2C0B222E68AFD1A0 FOREIGN KEY (exchange_id) REFERENCES analytics_exchange (id)');
        $this->addSql('ALTER TABLE sum_sub_reviews ADD CONSTRAINT FK_D42117509B6B5FBA FOREIGN KEY (account_id) REFERENCES accounts (id)');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE accounts DROP FOREIGN KEY FK_CAC89EAC7D16DE77');
        $this->addSql('ALTER TABLE analytics_account DROP FOREIGN KEY FK_D794DA5A7E3C61F9');
        $this->addSql('ALTER TABLE sum_sub_reviews DROP FOREIGN KEY FK_D42117509B6B5FBA');
        $this->addSql('ALTER TABLE account_to_instruments DROP FOREIGN KEY FK_9F3424629B6B5FBA');
        $this->addSql('ALTER TABLE analytics_capitalization DROP FOREIGN KEY FK_40BCD9D95DA1941');
        $this->addSql('ALTER TABLE analytics_account DROP FOREIGN KEY FK_D794DA5A68AFD1A0');
        $this->addSql('ALTER TABLE analytics_instrument DROP FOREIGN KEY FK_2C0B222E68AFD1A0');
        $this->addSql('ALTER TABLE account_to_instruments DROP FOREIGN KEY FK_9F342462CF11D9C');
        $this->addSql('DROP TABLE account_state');
        $this->addSql('DROP TABLE accounts');
        $this->addSql('DROP TABLE accounts_settings');
        $this->addSql('DROP TABLE analytics_account');
        $this->addSql('DROP TABLE account_to_instruments');
        $this->addSql('DROP TABLE analytics_asset');
        $this->addSql('DROP TABLE analytics_capitalization');
        $this->addSql('DROP TABLE analytics_exchange');
        $this->addSql('DROP TABLE analytics_instrument');
        $this->addSql('DROP TABLE news');
        $this->addSql('DROP TABLE setting_option_types');
        $this->addSql('DROP TABLE setting_options');
        $this->addSql('DROP TABLE settings');
        $this->addSql('DROP TABLE sum_sub_reviews');
        $this->addSql('DROP TABLE tokens');
        $this->addSql('DROP TABLE wallets');
    }
}
