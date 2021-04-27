<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20210322203028 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $sqlInsert1 = <<<SQL
INSERT INTO `userSettingOptions` (`id`, `typeId`, `name`) 
VALUES 
('1', '3', 'google2FASecret'),
('2', '2', 'google2FAEnabled'),
('3', '3', 'google2FAQRUrl'),
('4', '4', 'google2FARecoveryCodes'),
('5', '2', 'google2FAFirstAttempt');
SQL;
        $this->addSql($sqlInsert1);

        $sqlInsert2 = <<<SQL
INSERT INTO `userSettingOptionTypes` (`id`, `name`) 
VALUES 
('1', 'integer'),
('2', 'boolean'),
('3', 'string'),
('4', 'json');
SQL;
        $this->addSql($sqlInsert2);
    }

    public function down(Schema $schema) : void
    {
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql("DELETE FROM `userSettingOptions` WHERE id in ('1', '2', '3', '4', '5')");
        $this->addSql("DELETE FROM `userSettingOptionTypes` WHERE id in ('1', '2', '3', '4')");

        $this->addSql('DROP TABLE userSettingOptionTypes');
        $this->addSql('DROP TABLE userSettingOptions');
        $this->addSql('DROP TABLE userSettings');
        $this->addSql('ALTER TABLE accounts ADD google2FASecret VARCHAR(128) CHARACTER SET utf8 DEFAULT NULL COLLATE `utf8_unicode_ci` COMMENT \'Secret key to validation google 2FA code\', ADD google2FAEnabled TINYINT(1) DEFAULT \'1\' NOT NULL COMMENT \'Set the 0 to disable google 2FA\'');

    }
}
