<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20190712083532 extends AbstractMigration
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
        $this->addSql('ALTER TABLE client DROP FOREIGN KEY FK_C74404553481D195');
        $this->addSql('DROP INDEX IDX_C74404553481D195 ON client');
        $this->addSql('ALTER TABLE client DROP job_offer_id, CHANGE company_name company_name VARCHAR(255) DEFAULT NULL, CHANGE company_type company_type VARCHAR(255) DEFAULT NULL, CHANGE contact_name contact_name VARCHAR(255) DEFAULT NULL, CHANGE contact_job contact_job VARCHAR(255) DEFAULT NULL, CHANGE contact_email contact_email VARCHAR(255) DEFAULT NULL, CHANGE contact_phone_number contact_phone_number INT DEFAULT NULL, CHANGE created_at created_at DATETIME DEFAULT NULL, CHANGE updated_at updated_at DATETIME DEFAULT NULL');
        $this->addSql('ALTER TABLE job_offer DROP FOREIGN KEY FK_288A3A4EB6121583');
        $this->addSql('DROP INDEX IDX_288A3A4EB6121583 ON job_offer');
        $this->addSql('ALTER TABLE job_offer ADD client_id INT DEFAULT NULL, DROP candidature_id, CHANGE reference reference VARCHAR(255) DEFAULT NULL, CHANGE title title VARCHAR(255) DEFAULT NULL, CHANGE active active TINYINT(1) DEFAULT NULL, CHANGE location location VARCHAR(255) DEFAULT NULL, CHANGE salary salary DOUBLE PRECISION DEFAULT NULL, CHANGE created_at created_at DATETIME DEFAULT NULL, CHANGE updated_at updated_at DATETIME DEFAULT NULL, CHANGE closed_at closed_at DATETIME DEFAULT NULL, CHANGE job_category job_category VARCHAR(255) DEFAULT NULL, CHANGE type type VARCHAR(255) DEFAULT NULL');
        $this->addSql('ALTER TABLE job_offer ADD CONSTRAINT FK_288A3A4E19EB6921 FOREIGN KEY (client_id) REFERENCES client (id)');
        $this->addSql('CREATE INDEX IDX_288A3A4E19EB6921 ON job_offer (client_id)');
        $this->addSql('ALTER TABLE job_category CHANGE name name VARCHAR(255) DEFAULT NULL');
        $this->addSql('ALTER TABLE candidature ADD job_offer_id INT DEFAULT NULL, CHANGE user_id user_id INT DEFAULT NULL, CHANGE created_at created_at DATETIME DEFAULT NULL, CHANGE updated_at updated_at DATETIME DEFAULT NULL');
        $this->addSql('ALTER TABLE candidature ADD CONSTRAINT FK_E33BD3B83481D195 FOREIGN KEY (job_offer_id) REFERENCES job_offer (id)');
        $this->addSql('CREATE INDEX IDX_E33BD3B83481D195 ON candidature (job_offer_id)');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE candidature DROP FOREIGN KEY FK_E33BD3B83481D195');
        $this->addSql('DROP INDEX IDX_E33BD3B83481D195 ON candidature');
        $this->addSql('ALTER TABLE candidature DROP job_offer_id, CHANGE user_id user_id INT DEFAULT NULL, CHANGE created_at created_at DATETIME DEFAULT \'NULL\', CHANGE updated_at updated_at DATETIME DEFAULT NULL');
        $this->addSql('ALTER TABLE client ADD job_offer_id INT DEFAULT NULL, CHANGE company_name company_name VARCHAR(255) DEFAULT NULL COLLATE utf8mb4_unicode_ci, CHANGE company_type company_type VARCHAR(255) DEFAULT NULL COLLATE utf8mb4_unicode_ci, CHANGE contact_name contact_name VARCHAR(255) DEFAULT NULL COLLATE utf8mb4_unicode_ci, CHANGE contact_job contact_job VARCHAR(255) DEFAULT NULL COLLATE utf8mb4_unicode_ci, CHANGE contact_email contact_email VARCHAR(255) DEFAULT NULL COLLATE utf8mb4_unicode_ci, CHANGE contact_phone_number contact_phone_number INT DEFAULT NULL, CHANGE created_at created_at DATETIME DEFAULT NULL, CHANGE updated_at updated_at DATETIME DEFAULT NULL');
        $this->addSql('ALTER TABLE client ADD CONSTRAINT FK_C74404553481D195 FOREIGN KEY (job_offer_id) REFERENCES job_offer (id)');
        $this->addSql('CREATE INDEX IDX_C74404553481D195 ON client (job_offer_id)');
        $this->addSql('ALTER TABLE job_category CHANGE name name VARCHAR(255) DEFAULT NULL COLLATE utf8mb4_unicode_ci');
        $this->addSql('ALTER TABLE job_offer DROP FOREIGN KEY FK_288A3A4E19EB6921');
        $this->addSql('DROP INDEX IDX_288A3A4E19EB6921 ON job_offer');
        $this->addSql('ALTER TABLE job_offer ADD candidature_id INT DEFAULT NULL, DROP client_id, CHANGE reference reference VARCHAR(255) DEFAULT NULL COLLATE utf8mb4_unicode_ci, CHANGE title title VARCHAR(255) DEFAULT NULL COLLATE utf8mb4_unicode_ci, CHANGE active active TINYINT(1) DEFAULT NULL, CHANGE type type VARCHAR(255) DEFAULT NULL COLLATE utf8mb4_unicode_ci, CHANGE location location VARCHAR(255) DEFAULT NULL COLLATE utf8mb4_unicode_ci, CHANGE salary salary DOUBLE PRECISION DEFAULT NULL, CHANGE created_at created_at DATETIME DEFAULT NULL, CHANGE updated_at updated_at DATETIME DEFAULT NULL, CHANGE closed_at closed_at DATETIME DEFAULT NULL, CHANGE job_category job_category VARCHAR(255) DEFAULT NULL COLLATE utf8mb4_unicode_ci');
        $this->addSql('ALTER TABLE job_offer ADD CONSTRAINT FK_288A3A4EB6121583 FOREIGN KEY (candidature_id) REFERENCES candidature (id)');
        $this->addSql('CREATE INDEX IDX_288A3A4EB6121583 ON job_offer (candidature_id)');
        $this->addSql('ALTER TABLE user CHANGE email email VARCHAR(255) DEFAULT NULL COLLATE utf8mb4_unicode_ci, CHANGE password password VARCHAR(255) DEFAULT NULL COLLATE utf8mb4_unicode_ci, CHANGE gender gender VARCHAR(255) DEFAULT NULL COLLATE utf8mb4_unicode_ci, CHANGE first_name first_name VARCHAR(255) DEFAULT NULL COLLATE utf8mb4_unicode_ci, CHANGE last_name last_name VARCHAR(255) DEFAULT NULL COLLATE utf8mb4_unicode_ci, CHANGE phone_number phone_number INT DEFAULT NULL, CHANGE profile_picture profile_picture VARCHAR(255) DEFAULT NULL COLLATE utf8mb4_unicode_ci, CHANGE current_location current_location VARCHAR(255) DEFAULT NULL COLLATE utf8mb4_unicode_ci, CHANGE address address VARCHAR(255) DEFAULT NULL COLLATE utf8mb4_unicode_ci, CHANGE country country VARCHAR(255) DEFAULT NULL COLLATE utf8mb4_unicode_ci, CHANGE nationality nationality VARCHAR(255) DEFAULT NULL COLLATE utf8mb4_unicode_ci, CHANGE birth_date birth_date DATETIME DEFAULT NULL, CHANGE birth_place birth_place VARCHAR(255) DEFAULT NULL COLLATE utf8mb4_unicode_ci, CHANGE passport passport VARCHAR(255) DEFAULT NULL COLLATE utf8mb4_unicode_ci, CHANGE resume resume VARCHAR(255) DEFAULT NULL COLLATE utf8mb4_unicode_ci, CHANGE experience experience VARCHAR(255) DEFAULT NULL COLLATE utf8mb4_unicode_ci, CHANGE created_at created_at DATETIME DEFAULT NULL, CHANGE updated_at updated_at DATETIME DEFAULT NULL, CHANGE is_admin is_admin TINYINT(1) DEFAULT NULL, CHANGE availability availability TINYINT(1) DEFAULT NULL, CHANGE job_category job_category VARCHAR(255) DEFAULT NULL COLLATE utf8mb4_unicode_ci');
    }
}
