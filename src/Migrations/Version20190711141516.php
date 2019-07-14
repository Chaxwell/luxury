<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\Migrations\AbstractMigration;
use Doctrine\DBAL\Schema\Schema;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20190711141516 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE user CHANGE email email VARCHAR(255) DEFAULT NULL, CHANGE password password VARCHAR(255) DEFAULT NULL, CHANGE gender gender VARCHAR(255) DEFAULT NULL, CHANGE first_name first_name VARCHAR(255) DEFAULT NULL, CHANGE last_name last_name VARCHAR(255) DEFAULT NULL, CHANGE phone_number phone_number INT DEFAULT NULL, CHANGE profile_picture profile_picture VARCHAR(255) DEFAULT NULL, CHANGE current_location current_location VARCHAR(255) DEFAULT NULL, CHANGE address address VARCHAR(255) DEFAULT NULL, CHANGE country country VARCHAR(255) DEFAULT NULL, CHANGE nationality nationality VARCHAR(255) DEFAULT NULL, CHANGE birth_date birth_date DATETIME DEFAULT NULL, CHANGE birth_place birth_place VARCHAR(255) DEFAULT NULL, CHANGE passport passport VARCHAR(255) DEFAULT NULL, CHANGE resume resume VARCHAR(255) DEFAULT NULL, CHANGE experience experience VARCHAR(255) DEFAULT NULL, CHANGE created_at created_at DATETIME DEFAULT NULL, CHANGE updated_at updated_at DATETIME DEFAULT NULL, CHANGE is_admin is_admin TINYINT(1) DEFAULT NULL, CHANGE availability availability TINYINT(1) DEFAULT NULL, CHANGE job_category job_category VARCHAR(255) DEFAULT NULL');
        $this->addSql('ALTER TABLE client CHANGE job_offer_id job_offer_id INT DEFAULT NULL, CHANGE company_name company_name VARCHAR(255) DEFAULT NULL, CHANGE company_type company_type VARCHAR(255) DEFAULT NULL, CHANGE contact_name contact_name VARCHAR(255) DEFAULT NULL, CHANGE contact_job contact_job VARCHAR(255) DEFAULT NULL, CHANGE contact_email contact_email VARCHAR(255) DEFAULT NULL, CHANGE contact_phone_number contact_phone_number INT DEFAULT NULL, CHANGE created_at created_at DATETIME DEFAULT NULL, CHANGE updated_at updated_at DATETIME DEFAULT NULL');
        $this->addSql('ALTER TABLE job_category CHANGE name name VARCHAR(255) DEFAULT NULL');
        $this->addSql('ALTER TABLE job_offer ADD type VARCHAR(255) DEFAULT NULL, DROP types, CHANGE candidature_id candidature_id INT DEFAULT NULL, CHANGE reference reference VARCHAR(255) DEFAULT NULL, CHANGE title title VARCHAR(255) DEFAULT NULL, CHANGE active active TINYINT(1) DEFAULT NULL, CHANGE location location VARCHAR(255) DEFAULT NULL, CHANGE salary salary DOUBLE PRECISION DEFAULT NULL, CHANGE created_at created_at DATETIME DEFAULT NULL, CHANGE updated_at updated_at DATETIME DEFAULT NULL, CHANGE closed_at closed_at DATETIME DEFAULT NULL, CHANGE job_category job_category VARCHAR(255) DEFAULT NULL');
        $this->addSql('ALTER TABLE candidature CHANGE user_id user_id INT DEFAULT NULL, CHANGE created_at created_at DATETIME DEFAULT NULL, CHANGE updated_at updated_at DATETIME DEFAULT NULL');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE candidature CHANGE user_id user_id INT DEFAULT NULL, CHANGE created_at created_at DATETIME DEFAULT NULL, CHANGE updated_at updated_at DATETIME DEFAULT NULL');
        $this->addSql('ALTER TABLE client CHANGE job_offer_id job_offer_id INT DEFAULT NULL, CHANGE company_name company_name VARCHAR(255) DEFAULT NULL COLLATE utf8mb4_unicode_ci, CHANGE company_type company_type VARCHAR(255) DEFAULT NULL COLLATE utf8mb4_unicode_ci, CHANGE contact_name contact_name VARCHAR(255) DEFAULT NULL COLLATE utf8mb4_unicode_ci, CHANGE contact_job contact_job VARCHAR(255) DEFAULT NULL COLLATE utf8mb4_unicode_ci, CHANGE contact_email contact_email VARCHAR(255) DEFAULT NULL COLLATE utf8mb4_unicode_ci, CHANGE contact_phone_number contact_phone_number INT DEFAULT NULL, CHANGE created_at created_at DATETIME DEFAULT NULL, CHANGE updated_at updated_at DATETIME DEFAULT NULL');
        $this->addSql('ALTER TABLE job_category CHANGE name name VARCHAR(255) DEFAULT NULL COLLATE utf8mb4_unicode_ci');
        $this->addSql('ALTER TABLE job_offer ADD types LONGTEXT DEFAULT NULL COLLATE utf8mb4_unicode_ci COMMENT \'(DC2Type:array)\', DROP type, CHANGE candidature_id candidature_id INT DEFAULT NULL, CHANGE reference reference VARCHAR(255) DEFAULT NULL COLLATE utf8mb4_unicode_ci, CHANGE title title VARCHAR(255) DEFAULT NULL COLLATE utf8mb4_unicode_ci, CHANGE active active TINYINT(1) DEFAULT NULL, CHANGE location location VARCHAR(255) DEFAULT NULL COLLATE utf8mb4_unicode_ci, CHANGE salary salary DOUBLE PRECISION DEFAULT NULL, CHANGE created_at created_at DATETIME DEFAULT NULL, CHANGE updated_at updated_at DATETIME DEFAULT NULL, CHANGE closed_at closed_at DATETIME DEFAULT NULL, CHANGE job_category job_category VARCHAR(255) DEFAULT NULL COLLATE utf8mb4_unicode_ci');
        $this->addSql('ALTER TABLE user CHANGE email email VARCHAR(255) DEFAULT NULL COLLATE utf8mb4_unicode_ci, CHANGE password password VARCHAR(255) DEFAULT NULL COLLATE utf8mb4_unicode_ci, CHANGE gender gender VARCHAR(255) DEFAULT NULL COLLATE utf8mb4_unicode_ci, CHANGE first_name first_name VARCHAR(255) DEFAULT NULL COLLATE utf8mb4_unicode_ci, CHANGE last_name last_name VARCHAR(255) DEFAULT NULL COLLATE utf8mb4_unicode_ci, CHANGE phone_number phone_number INT DEFAULT NULL, CHANGE profile_picture profile_picture VARCHAR(255) DEFAULT NULL COLLATE utf8mb4_unicode_ci, CHANGE current_location current_location VARCHAR(255) DEFAULT NULL COLLATE utf8mb4_unicode_ci, CHANGE address address VARCHAR(255) DEFAULT NULL COLLATE utf8mb4_unicode_ci, CHANGE country country VARCHAR(255) DEFAULT NULL COLLATE utf8mb4_unicode_ci, CHANGE nationality nationality VARCHAR(255) DEFAULT NULL COLLATE utf8mb4_unicode_ci, CHANGE birth_date birth_date DATETIME DEFAULT NULL, CHANGE birth_place birth_place VARCHAR(255) DEFAULT NULL COLLATE utf8mb4_unicode_ci, CHANGE passport passport VARCHAR(255) DEFAULT NULL COLLATE utf8mb4_unicode_ci, CHANGE resume resume VARCHAR(255) DEFAULT NULL COLLATE utf8mb4_unicode_ci, CHANGE experience experience VARCHAR(255) DEFAULT NULL COLLATE utf8mb4_unicode_ci, CHANGE created_at created_at DATETIME DEFAULT NULL, CHANGE updated_at updated_at DATETIME DEFAULT NULL, CHANGE is_admin is_admin TINYINT(1) DEFAULT NULL, CHANGE availability availability TINYINT(1) DEFAULT NULL, CHANGE job_category job_category VARCHAR(255) DEFAULT NULL COLLATE utf8mb4_unicode_ci');
    }
}
