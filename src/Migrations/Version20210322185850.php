<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20210322185850 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE sum_sub_reviews (id INT AUTO_INCREMENT NOT NULL, account_id INT NOT NULL, applicantId VARCHAR(50) NOT NULL, inspectionId VARCHAR(50) NOT NULL, corellationId VARCHAR(100) NOT NULL, reviewResult VARCHAR(10) NOT NULL, moderationComment LONGTEXT DEFAULT NULL, clientComment LONGTEXT DEFAULT NULL, rejectLabels LONGTEXT DEFAULT NULL COMMENT \'(DC2Type:json)\', status VARCHAR(30) NOT NULL, createdAt DATETIME NOT NULL, INDEX IDX_D42117509B6B5FBA (account_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET UTF8 COLLATE `UTF8_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE sum_sub_reviews ADD CONSTRAINT FK_D42117509B6B5FBA FOREIGN KEY (account_id) REFERENCES accounts (id)');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('DROP TABLE sum_sub_reviews');
    }
}
