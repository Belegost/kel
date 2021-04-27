<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20210427194822 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('DROP INDEX account_id_INDEX ON accounts_settings');
        $this->addSql('DROP INDEX setting_id_INDEX ON accounts_settings');
        $this->addSql('ALTER TABLE accounts_settings CHANGE id id INT AUTO_INCREMENT NOT NULL, CHANGE account_id `account_id` INT NOT NULL, CHANGE setting_id `setting_id` INT NOT NULL');
        $this->addSql('ALTER TABLE setting_option_types CHANGE id id SMALLINT NOT NULL, CHANGE name `name` VARCHAR(64) NOT NULL');
        $this->addSql('ALTER TABLE setting_option_types RENAME INDEX name_unique TO UNIQ_7857DB5E999517A');
        $this->addSql('DROP INDEX type_id_INDEX ON setting_options');
        $this->addSql('ALTER TABLE setting_options CHANGE id id SMALLINT NOT NULL, CHANGE type_id `type_id` SMALLINT NOT NULL, CHANGE name `name` VARCHAR(128) NOT NULL');
        $this->addSql('ALTER TABLE setting_options RENAME INDEX name_unique TO UNIQ_88674A6F999517A');
        $this->addSql('DROP INDEX option_id_INDEX ON settings');
        $this->addSql('ALTER TABLE settings CHANGE id id INT AUTO_INCREMENT NOT NULL, CHANGE option_id `option_id` SMALLINT NOT NULL, CHANGE value `value` LONGTEXT NOT NULL');
        $this->addSql('DROP INDEX hash_UNIQUE ON tokens');
        $this->addSql('ALTER TABLE tokens CHANGE id id INT AUTO_INCREMENT NOT NULL, CHANGE hash hash VARCHAR(64) NOT NULL, CHANGE data data JSON DEFAULT NULL COMMENT \'(DC2Type:json_array)\', CHANGE expired_time expired_time DATETIME DEFAULT NULL, CHANGE created_time created_time DATETIME NOT NULL');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE accounts_settings CHANGE id id INT UNSIGNED AUTO_INCREMENT NOT NULL COMMENT \'Internal Id\', CHANGE `account_id` account_id INT UNSIGNED NOT NULL COMMENT \'Refers to the accounts.id\', CHANGE `setting_id` setting_id INT UNSIGNED NOT NULL COMMENT \'Refers to the settings.id\'');
        $this->addSql('CREATE INDEX account_id_INDEX ON accounts_settings (account_id)');
        $this->addSql('CREATE INDEX setting_id_INDEX ON accounts_settings (setting_id)');
        $this->addSql('ALTER TABLE setting_option_types CHANGE id id SMALLINT UNSIGNED NOT NULL COMMENT \'Internal id\', CHANGE `name` name VARCHAR(64) CHARACTER SET utf8mb4 NOT NULL COLLATE `utf8mb4_0900_ai_ci` COMMENT \'Name of the Option Type\'');
        $this->addSql('ALTER TABLE setting_option_types RENAME INDEX uniq_7857db5e999517a TO name_UNIQUE');
        $this->addSql('ALTER TABLE setting_options CHANGE id id SMALLINT UNSIGNED NOT NULL COMMENT \'Internal Id\', CHANGE `type_id` type_id SMALLINT UNSIGNED NOT NULL COMMENT \'Refers to the user_setting_option_types.id\', CHANGE `name` name VARCHAR(128) CHARACTER SET utf8mb4 NOT NULL COLLATE `utf8mb4_0900_ai_ci` COMMENT \'Name of the Setting Option\'');
        $this->addSql('CREATE INDEX type_id_INDEX ON setting_options (type_id)');
        $this->addSql('ALTER TABLE setting_options RENAME INDEX uniq_88674a6f999517a TO name_UNIQUE');
        $this->addSql('ALTER TABLE settings CHANGE id id INT UNSIGNED AUTO_INCREMENT NOT NULL COMMENT \'Internal Id\', CHANGE `option_id` option_id SMALLINT UNSIGNED NOT NULL COMMENT \'Refers to the setting_options.id\', CHANGE `value` value LONGTEXT CHARACTER SET utf8mb4 NOT NULL COLLATE `utf8mb4_0900_ai_ci` COMMENT \'Value of the setting\'');
        $this->addSql('CREATE INDEX option_id_INDEX ON settings (option_id)');
        $this->addSql('ALTER TABLE tokens CHANGE id id INT UNSIGNED AUTO_INCREMENT NOT NULL COMMENT \'Internal id\', CHANGE hash hash VARCHAR(64) CHARACTER SET utf8mb4 NOT NULL COLLATE `utf8mb4_0900_ai_ci` COMMENT \'Hash identifier the token data\', CHANGE data data LONGTEXT CHARACTER SET utf8mb4 DEFAULT NULL COLLATE `utf8mb4_0900_ai_ci` COMMENT \'Token data in json format\', CHANGE expired_time expired_time DATETIME DEFAULT NULL COMMENT \'Expiration time when token data will not be available\', CHANGE created_time created_time DATETIME DEFAULT CURRENT_TIMESTAMP NOT NULL COMMENT \'Creation time when token was created\'');
        $this->addSql('CREATE UNIQUE INDEX hash_UNIQUE ON tokens (hash)');
    }
}
