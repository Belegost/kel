<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20210326201936 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        $this->addSql( <<<SQL
ALTER TABLE `userSettingOptionTypes` 
CHARACTER SET = utf8mb4, 
CHANGE COLUMN `id` `id` SMALLINT(3) UNSIGNED NOT NULL COMMENT 'Internal id',
CHANGE COLUMN `name` `name` VARCHAR(64) NOT NULL COMMENT 'Name of the Option Type', 
COMMENT = 'Dictionary of the Option Types', 
RENAME TO  `setting_option_types`;
SQL
        );

        $this->addSql( <<<SQL
ALTER TABLE `userSettingOptions` 
CHARACTER SET = utf8mb4,
CHANGE COLUMN `id` `id` SMALLINT(3) UNSIGNED NOT NULL COMMENT 'Internal Id',
CHANGE COLUMN `typeId` `type_id` SMALLINT(3) UNSIGNED NOT NULL COMMENT 'Refers to the user_setting_option_types.id',
CHANGE COLUMN `name` `name` VARCHAR(128) NOT NULL COMMENT 'Name of the Setting Option', 
ADD INDEX `type_id_INDEX` (`type_id` ASC),
COMMENT = 'Contains the Setting Options', 
RENAME TO `setting_options`;
SQL
        );

        $this->addSql( <<< SQL
 CREATE TABLE `settings` (
 `id` INT(11) UNSIGNED AUTO_INCREMENT NOT NULL COMMENT 'Internal Id', 
 `option_id` SMALLINT(3) UNSIGNED NOT NULL COMMENT 'Refers to the setting_options.id', 
 `value` LONGTEXT NOT NULL COMMENT 'Value of the setting', 
 PRIMARY KEY(`id`),
 INDEX `option_id_INDEX` (`option_id` ASC)
 ) ENGINE = InnoDB DEFAULT CHARACTER SET utf8mb4 COMMENT = 'Contains the settings values';
 SQL
        );

        $this->addSql( <<<SQL
ALTER TABLE `userSettings` 
CHARACTER SET = utf8mb4,
CHANGE COLUMN `id` `id` INT(11) UNSIGNED NOT NULL AUTO_INCREMENT COMMENT 'Internal Id',
CHANGE COLUMN `userId` `account_id` INT(11) UNSIGNED NOT NULL COMMENT 'Refers to the accounts.id',
CHANGE COLUMN `optionId` `setting_id` INT(11) UNSIGNED NOT NULL COMMENT 'Refers to the settings.id',
DROP COLUMN `value`,
ADD INDEX `account_id_INDEX` (`account_id` ASC),
ADD INDEX `setting_id_INDEX` (`setting_id` ASC), 
COMMENT = 'Contains the Accounts Settings', 
RENAME TO `accounts_settings`;
SQL
        );

        $this->addSql( <<<SQL
ALTER TABLE `tokens` 
CHARACTER SET = utf8mb4,
CHANGE COLUMN `id` `id` INT(11) UNSIGNED NOT NULL AUTO_INCREMENT COMMENT 'Internal id',
CHANGE COLUMN `hash` `hash` VARCHAR(64) NOT NULL COMMENT 'Hash identifier the token data',
CHANGE COLUMN `data` `data` LONGTEXT DEFAULT NULL COMMENT 'Token data in json format',
CHANGE COLUMN `expired_time` `expired_time` TIMESTAMP NULL DEFAULT NULL COMMENT 'Expiration time when token data will not be available' AFTER `data`,
CHANGE COLUMN `created_time` `created_time` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP COMMENT 'Creation time when token was created',
ADD UNIQUE INDEX `hash_UNIQUE` (`hash` ASC), 
COMMENT = 'Contains the token data';
SQL
        );

        $this->addSql( <<<SQL
INSERT INTO `setting_options` (`id`, `type_id`, `name`) 
VALUES 
('6', '2', 'isEmailConfirmed');
SQL
        );

        $this->addSql("DELETE FROM `accounts_settings`");
    }

    public function down(Schema $schema) : void
    {
        $this->addSql("DELETE FROM `setting_options` WHERE id='6'");

        $this->addSql( <<<SQL
ALTER TABLE `setting_option_types`
ADD UNIQUE INDEX `UNIQ_67A0A9B8999517A` (`name` ASC),
DROP INDEX `name_UNIQUE`,                                                                                       
RENAME TO `userSettingOptionTypes`;
SQL
        );

        $this->addSql( <<<SQL
ALTER TABLE  `setting_options`
CHANGE COLUMN `type_id` `typeId` SMALLINT NOT NULL,
DROP INDEX `name_UNIQUE_`, 
ADD UNIQUE INDEX `UNIQ_50FD4803999517A` (`name` ASC),
DROP INDEX `type_id_INDEX`,
RENAME TO  `userSettingOptions`;
SQL
        );

        $this->addSql('DROP TABLE `settings`');

        $this->addSql( <<<SQL
ALTER TABLE `accounts_settings`
CHANGE COLUMN `account_id` `userId` INT NOT NULL,
CHANGE COLUMN `setting_id` `optionId` SMALLINT NOT NULL,
ADD COLUMN `value` LONGTEXT NOT NULL,
DROP INDEX `account_id_INDEX`,
DROP INDEX `setting_id_INDEX`,
RENAME TO `userSettings`;
SQL
        );

        $this->addSql( <<<SQL
ALTER TABLE `tokens` 
CHARACTER SET = utf8,
CHANGE COLUMN `id` `id` INT(11) NOT NULL AUTO_INCREMENT,
CHANGE COLUMN `hash` `hash` VARCHAR(255) NOT NULL,
CHANGE COLUMN `data` `data` LONGTEXT NOT NULL,
CHANGE COLUMN `expired_time` `expired_time` DATETIME DEFAULT NULL,
CHANGE COLUMN `created_time` `created_time` DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
DROP INDEX `hash_UNIQUE`;
SQL
        );
    }
}
