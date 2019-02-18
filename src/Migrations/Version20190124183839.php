<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20190124183839 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('CREATE TABLE allocated_topic (allocated_id INT AUTO_INCREMENT NOT NULL, student_id INT UNSIGNED DEFAULT NULL, topic_id INT DEFAULT NULL, allocated_date DATETIME NOT NULL, UNIQUE INDEX UNIQ_5FA54B97CB944F1A (student_id), UNIQUE INDEX UNIQ_5FA54B971F55203D (topic_id), PRIMARY KEY(allocated_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE topic (topic_id INT AUTO_INCREMENT NOT NULL, supervisor_id INT DEFAULT NULL, name VARCHAR(255) NOT NULL, domain VARCHAR(150) NOT NULL, added_date DATETIME NOT NULL, INDEX IDX_9D40DE1B19E9AC5F (supervisor_id), PRIMARY KEY(topic_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE = InnoDB');
        $this->addSql('ALTER TABLE allocated_topic ADD CONSTRAINT FK_5FA54B97CB944F1A FOREIGN KEY (student_id) REFERENCES student (student_id)');
        $this->addSql('ALTER TABLE allocated_topic ADD CONSTRAINT FK_5FA54B971F55203D FOREIGN KEY (topic_id) REFERENCES topic (topic_id)');
        $this->addSql('ALTER TABLE topic ADD CONSTRAINT FK_9D40DE1B19E9AC5F FOREIGN KEY (supervisor_id) REFERENCES supervisor (supervisor_id)');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE allocated_topic DROP FOREIGN KEY FK_5FA54B971F55203D');
        $this->addSql('DROP TABLE allocated_topic');
        $this->addSql('DROP TABLE topic');
    }
}
