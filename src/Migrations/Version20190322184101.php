<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20190322184101 extends AbstractMigration
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
        $this->addSql('CREATE TABLE domain (id INT AUTO_INCREMENT NOT NULL, name VARCHAR(255) NOT NULL, created_date DATETIME NOT NULL, UNIQUE INDEX UNIQ_A7A91E0B5E237E06 (name), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE student (student_id INT UNSIGNED AUTO_INCREMENT NOT NULL, matric_no INT UNSIGNED NOT NULL, first_name VARCHAR(20) NOT NULL, last_name VARCHAR(20) NOT NULL, email VARCHAR(50) NOT NULL, session VARCHAR(10) NOT NULL, first_interest VARCHAR(50) NOT NULL, second_interest VARCHAR(50) NOT NULL, third_interest VARCHAR(50) NOT NULL, registered DATETIME NOT NULL, UNIQUE INDEX UNIQ_B723AF33F58B9436 (matric_no), UNIQUE INDEX UNIQ_B723AF33E7927C74 (email), PRIMARY KEY(student_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE supervisor (supervisor_id INT AUTO_INCREMENT NOT NULL, title VARCHAR(4) NOT NULL, username VARCHAR(30) NOT NULL, first_name VARCHAR(20) NOT NULL, last_name VARCHAR(20) NOT NULL, phone_number VARCHAR(14) NOT NULL, email VARCHAR(50) NOT NULL, password VARCHAR(255) NOT NULL, capacity INT NOT NULL, registered DATETIME NOT NULL, UNIQUE INDEX UNIQ_4D9192F86B01BC5B (phone_number), UNIQUE INDEX UNIQ_4D9192F8E7927C74 (email), PRIMARY KEY(supervisor_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE topic (topic_id INT AUTO_INCREMENT NOT NULL, supervisor_id INT DEFAULT NULL, domain_id INT DEFAULT NULL, name VARCHAR(255) NOT NULL, added_date DATETIME NOT NULL, UNIQUE INDEX UNIQ_9D40DE1B5E237E06 (name), INDEX IDX_9D40DE1B19E9AC5F (supervisor_id), INDEX IDX_9D40DE1B115F0EE5 (domain_id), PRIMARY KEY(topic_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE = InnoDB');
        $this->addSql('ALTER TABLE allocated_topic ADD CONSTRAINT FK_5FA54B97CB944F1A FOREIGN KEY (student_id) REFERENCES student (student_id)');
        $this->addSql('ALTER TABLE allocated_topic ADD CONSTRAINT FK_5FA54B971F55203D FOREIGN KEY (topic_id) REFERENCES topic (topic_id)');
        $this->addSql('ALTER TABLE topic ADD CONSTRAINT FK_9D40DE1B19E9AC5F FOREIGN KEY (supervisor_id) REFERENCES supervisor (supervisor_id)');
        $this->addSql('ALTER TABLE topic ADD CONSTRAINT FK_9D40DE1B115F0EE5 FOREIGN KEY (domain_id) REFERENCES domain (id)');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE topic DROP FOREIGN KEY FK_9D40DE1B115F0EE5');
        $this->addSql('ALTER TABLE allocated_topic DROP FOREIGN KEY FK_5FA54B97CB944F1A');
        $this->addSql('ALTER TABLE topic DROP FOREIGN KEY FK_9D40DE1B19E9AC5F');
        $this->addSql('ALTER TABLE allocated_topic DROP FOREIGN KEY FK_5FA54B971F55203D');
        $this->addSql('DROP TABLE allocated_topic');
        $this->addSql('DROP TABLE domain');
        $this->addSql('DROP TABLE student');
        $this->addSql('DROP TABLE supervisor');
        $this->addSql('DROP TABLE topic');
    }
}
