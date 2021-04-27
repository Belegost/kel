<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20210320110428 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $sqlCreate1 = <<<SQL
CREATE TABLE IF NOT EXISTS `userSettings` (
  `id` INT(11) UNSIGNED NOT NULL AUTO_INCREMENT COMMENT 'Internal id',
  `userId` INT(11) UNSIGNED NOT NULL COMMENT 'Refrence to account.id',
  `optionId` SMALLINT(3) UNSIGNED NOT NULL COMMENT 'Refrence to userSettingOptions.id',
  `value` TEXT NOT NULL COMMENT 'Value of setting option',
  PRIMARY KEY (`id`),
  INDEX `userId_INDEX` (`userId` ASC),
  INDEX `optionId_INDEX` (`optionId` ASC)
) ENGINE = InnoDB DEFAULT CHARACTER SET = utf8mb4 COMMENT = 'Contains user settings';
SQL;
        $this->addSql($sqlCreate1);

        $sqlCreate2 = <<<SQL
CREATE TABLE IF NOT EXISTS `userSettingOptions` (
  `id` SMALLINT(3) UNSIGNED NOT NULL COMMENT 'Internal id',
  `typeId` SMALLINT(3) UNSIGNED NOT NULL COMMENT 'Refrence to userSettingOptionTypes.id',
  `name` VARCHAR(128) NOT NULL COMMENT 'Option name',
  PRIMARY KEY (`id`),
  UNIQUE KEY `name_UNIQUE` (`name`),
  INDEX `typeId_INDEX` (`typeId` ASC)
) ENGINE = InnoDB DEFAULT CHARACTER SET = utf8mb4 COMMENT = 'Contains the user setting options';
SQL;
        $this->addSql($sqlCreate2);

        $sqlCreate3 = <<<SQL
CREATE TABLE IF NOT EXISTS `userSettingOptionTypes` (
  `id` SMALLINT(3) UNSIGNED NOT NULL COMMENT 'Internal id',
  `name` VARCHAR(64) NOT NULL COMMENT 'Option name',
  PRIMARY KEY (`id`),
  UNIQUE KEY `name_UNIQUE` (`name`)
) ENGINE = InnoDB DEFAULT CHARACTER SET = utf8mb4 COMMENT = 'Contains the user setting option types';
SQL;
        $this->addSql($sqlCreate3);
    }

    public function down(Schema $schema) : void
    {
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('DROP TABLE IF EXISTS userSettings, userSettingOptions, userSettingOptionTypes');
    }

    public function isTransactional(): bool
    {
        return false;
    }
}
