<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20200806074238 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE client_contract ADD active TINYINT(1) NOT NULL, CHANGE clientname_id clientname_id INT DEFAULT NULL, CHANGE user_id user_id INT DEFAULT NULL, CHANGE endDate endDate DATE DEFAULT NULL, CHANGE updated_at updated_at DATETIME DEFAULT NULL, CHANGE vat vat DOUBLE PRECISION DEFAULT NULL');
        $this->addSql('ALTER TABLE contact CHANGE dateofbirth dateofbirth DATE DEFAULT NULL, CHANGE sexe sexe VARCHAR(255) DEFAULT NULL, CHANGE socialesecunumber socialesecunumber VARCHAR(255) DEFAULT NULL, CHANGE updated_at updated_at DATETIME DEFAULT NULL');
        $this->addSql('ALTER TABLE contact_end_client CHANGE updated_at updated_at DATETIME DEFAULT NULL, CHANGE vat_number vat_number VARCHAR(255) DEFAULT NULL');
        $this->addSql('ALTER TABLE user CHANGE contact_id contact_id INT DEFAULT NULL, CHANGE roles roles JSON NOT NULL, CHANGE invoice invoice VARCHAR(255) DEFAULT NULL, CHANGE updated_at updated_at DATETIME DEFAULT NULL, CHANGE reset_token reset_token VARCHAR(255) DEFAULT NULL');
        $this->addSql('ALTER TABLE invoice_creation_data CHANGE bank_holidays bank_holidays DOUBLE PRECISION DEFAULT NULL, CHANGE work_saturdays work_saturdays DOUBLE PRECISION DEFAULT NULL, CHANGE work_sundays work_sundays DOUBLE PRECISION DEFAULT NULL, CHANGE updated_at updated_at DATETIME DEFAULT NULL');
        $this->addSql('ALTER TABLE invoice_random CHANGE user_id user_id INT DEFAULT NULL, CHANGE units units DOUBLE PRECISION DEFAULT NULL, CHANGE amount amount DOUBLE PRECISION DEFAULT NULL, CHANGE rate rate DOUBLE PRECISION DEFAULT NULL, CHANGE updated_at updated_at DATETIME DEFAULT NULL, CHANGE status status VARCHAR(255) DEFAULT NULL, CHANGE paymentstatus paymentstatus VARCHAR(255) DEFAULT NULL');
        $this->addSql('ALTER TABLE invoice CHANGE contract_id contract_id INT DEFAULT NULL, CHANGE timesheet_id timesheet_id INT DEFAULT NULL, CHANGE updated_at updated_at DATETIME DEFAULT NULL, CHANGE bankholidayamount bankholidayamount DOUBLE PRECISION DEFAULT NULL, CHANGE saturdyamount saturdyamount DOUBLE PRECISION DEFAULT NULL, CHANGE sundayamount sundayamount DOUBLE PRECISION DEFAULT NULL, CHANGE businessdaysamount businessdaysamount DOUBLE PRECISION DEFAULT NULL, CHANGE invoicenumber invoicenumber INT DEFAULT NULL, CHANGE paymentstatus paymentstatus VARCHAR(255) DEFAULT NULL, CHANGE amountttc amountttc DOUBLE PRECISION DEFAULT NULL, CHANGE vatamount vatamount DOUBLE PRECISION DEFAULT NULL, CHANGE bank_holiday_rate bank_holiday_rate DOUBLE PRECISION DEFAULT NULL, CHANGE saturday_rate saturday_rate DOUBLE PRECISION DEFAULT NULL, CHANGE sunday_rate sunday_rate DOUBLE PRECISION DEFAULT NULL');
        $this->addSql('ALTER TABLE timesheet CHANGE nbr_of_bank_holidays nbr_of_bank_holidays INT DEFAULT NULL, CHANGE nbre_of_saturdays nbre_of_saturdays INT DEFAULT NULL, CHANGE nbre_of_sundays nbre_of_sundays INT DEFAULT NULL, CHANGE path path VARCHAR(255) DEFAULT NULL, CHANGE updated_at updated_at DATETIME DEFAULT NULL');
        $this->addSql('ALTER TABLE address CHANGE contact_id contact_id INT DEFAULT NULL, CHANGE country_id country_id INT DEFAULT NULL, CHANGE updated_at updated_at DATETIME DEFAULT NULL');
        $this->addSql('ALTER TABLE bank_details CHANGE contact_id contact_id INT DEFAULT NULL, CHANGE updated_at updated_at DATETIME DEFAULT NULL, CHANGE ibanstatement ibanstatement VARCHAR(255) DEFAULT NULL');
        $this->addSql('ALTER TABLE citizenship_details CHANGE contact_id contact_id INT DEFAULT NULL, CHANGE updated_at updated_at DATETIME DEFAULT NULL');
        $this->addSql('ALTER TABLE contract CHANGE contact_id contact_id INT DEFAULT NULL, CHANGE endDate endDate DATE DEFAULT NULL, CHANGE probationPeriodEndDate probationPeriodEndDate DATE DEFAULT NULL, CHANGE updated_at updated_at DATETIME DEFAULT NULL');
        $this->addSql('ALTER TABLE country CHANGE updated_at updated_at DATETIME DEFAULT NULL');
        $this->addSql('ALTER TABLE mail CHANGE contact_id contact_id INT DEFAULT NULL, CHANGE clientcontact_id clientcontact_id INT DEFAULT NULL, CHANGE updated_at updated_at DATETIME DEFAULT NULL');
        $this->addSql('ALTER TABLE payroll_package CHANGE updated_at updated_at DATETIME DEFAULT NULL');
        $this->addSql('ALTER TABLE phone CHANGE contact_id contact_id INT DEFAULT NULL, CHANGE clientcontact_id clientcontact_id INT DEFAULT NULL, CHANGE updated_at updated_at DATETIME DEFAULT NULL');
        $this->addSql('ALTER TABLE services CHANGE updated_at updated_at DATETIME DEFAULT NULL');
        $this->addSql('ALTER TABLE statement_file CHANGE user_id user_id INT DEFAULT NULL, CHANGE communication communication VARCHAR(255) DEFAULT NULL, CHANGE updated_at updated_at DATETIME DEFAULT NULL');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE address CHANGE contact_id contact_id INT DEFAULT NULL, CHANGE country_id country_id INT DEFAULT NULL, CHANGE updated_at updated_at DATETIME DEFAULT \'NULL\'');
        $this->addSql('ALTER TABLE bank_details CHANGE contact_id contact_id INT DEFAULT NULL, CHANGE updated_at updated_at DATETIME DEFAULT \'NULL\', CHANGE ibanstatement ibanstatement VARCHAR(255) CHARACTER SET utf8mb4 DEFAULT \'NULL\' COLLATE `utf8mb4_unicode_ci`');
        $this->addSql('ALTER TABLE citizenship_details CHANGE contact_id contact_id INT DEFAULT NULL, CHANGE updated_at updated_at DATETIME DEFAULT \'NULL\'');
        $this->addSql('ALTER TABLE client_contract DROP active, CHANGE clientname_id clientname_id INT DEFAULT NULL, CHANGE user_id user_id INT DEFAULT NULL, CHANGE endDate endDate DATE DEFAULT \'NULL\', CHANGE updated_at updated_at DATETIME DEFAULT \'NULL\', CHANGE vat vat DOUBLE PRECISION DEFAULT \'NULL\'');
        $this->addSql('ALTER TABLE contact CHANGE dateofbirth dateofbirth DATE DEFAULT \'NULL\', CHANGE sexe sexe VARCHAR(255) CHARACTER SET utf8mb4 DEFAULT \'NULL\' COLLATE `utf8mb4_unicode_ci`, CHANGE socialesecunumber socialesecunumber VARCHAR(255) CHARACTER SET utf8mb4 DEFAULT \'NULL\' COLLATE `utf8mb4_unicode_ci`, CHANGE updated_at updated_at DATETIME DEFAULT \'NULL\'');
        $this->addSql('ALTER TABLE contact_end_client CHANGE updated_at updated_at DATETIME DEFAULT \'NULL\', CHANGE vat_number vat_number VARCHAR(255) CHARACTER SET utf8mb4 DEFAULT \'NULL\' COLLATE `utf8mb4_unicode_ci`');
        $this->addSql('ALTER TABLE contract CHANGE contact_id contact_id INT DEFAULT NULL, CHANGE endDate endDate DATE DEFAULT \'NULL\', CHANGE probationPeriodEndDate probationPeriodEndDate DATE DEFAULT \'NULL\', CHANGE updated_at updated_at DATETIME DEFAULT \'NULL\'');
        $this->addSql('ALTER TABLE country CHANGE updated_at updated_at DATETIME DEFAULT \'NULL\'');
        $this->addSql('ALTER TABLE invoice CHANGE contract_id contract_id INT DEFAULT NULL, CHANGE timesheet_id timesheet_id INT DEFAULT NULL, CHANGE updated_at updated_at DATETIME DEFAULT \'NULL\', CHANGE bankholidayamount bankholidayamount DOUBLE PRECISION DEFAULT \'NULL\', CHANGE saturdyamount saturdyamount DOUBLE PRECISION DEFAULT \'NULL\', CHANGE sundayamount sundayamount DOUBLE PRECISION DEFAULT \'NULL\', CHANGE businessdaysamount businessdaysamount DOUBLE PRECISION DEFAULT \'NULL\', CHANGE invoicenumber invoicenumber INT DEFAULT NULL, CHANGE paymentstatus paymentstatus VARCHAR(255) CHARACTER SET utf8mb4 DEFAULT \'NULL\' COLLATE `utf8mb4_unicode_ci`, CHANGE amountttc amountttc DOUBLE PRECISION DEFAULT \'NULL\', CHANGE vatamount vatamount DOUBLE PRECISION DEFAULT \'NULL\', CHANGE bank_holiday_rate bank_holiday_rate DOUBLE PRECISION DEFAULT \'NULL\', CHANGE saturday_rate saturday_rate DOUBLE PRECISION DEFAULT \'NULL\', CHANGE sunday_rate sunday_rate DOUBLE PRECISION DEFAULT \'NULL\'');
        $this->addSql('ALTER TABLE invoice_creation_data CHANGE bank_holidays bank_holidays DOUBLE PRECISION DEFAULT \'NULL\', CHANGE work_saturdays work_saturdays DOUBLE PRECISION DEFAULT \'NULL\', CHANGE work_sundays work_sundays DOUBLE PRECISION DEFAULT \'NULL\', CHANGE updated_at updated_at DATETIME DEFAULT \'NULL\'');
        $this->addSql('ALTER TABLE invoice_random CHANGE user_id user_id INT DEFAULT NULL, CHANGE units units DOUBLE PRECISION DEFAULT \'NULL\', CHANGE amount amount DOUBLE PRECISION DEFAULT \'NULL\', CHANGE rate rate DOUBLE PRECISION DEFAULT \'NULL\', CHANGE updated_at updated_at DATETIME DEFAULT \'NULL\', CHANGE status status VARCHAR(255) CHARACTER SET utf8mb4 DEFAULT \'NULL\' COLLATE `utf8mb4_unicode_ci`, CHANGE paymentstatus paymentstatus VARCHAR(255) CHARACTER SET utf8mb4 DEFAULT \'NULL\' COLLATE `utf8mb4_unicode_ci`');
        $this->addSql('ALTER TABLE mail CHANGE contact_id contact_id INT DEFAULT NULL, CHANGE clientcontact_id clientcontact_id INT DEFAULT NULL, CHANGE updated_at updated_at DATETIME DEFAULT \'NULL\'');
        $this->addSql('ALTER TABLE payroll_package CHANGE updated_at updated_at DATETIME DEFAULT \'NULL\'');
        $this->addSql('ALTER TABLE phone CHANGE contact_id contact_id INT DEFAULT NULL, CHANGE clientcontact_id clientcontact_id INT DEFAULT NULL, CHANGE updated_at updated_at DATETIME DEFAULT \'NULL\'');
        $this->addSql('ALTER TABLE services CHANGE updated_at updated_at DATETIME DEFAULT \'NULL\'');
        $this->addSql('ALTER TABLE statement_file CHANGE user_id user_id INT DEFAULT NULL, CHANGE communication communication VARCHAR(255) CHARACTER SET utf8mb4 DEFAULT \'NULL\' COLLATE `utf8mb4_unicode_ci`, CHANGE updated_at updated_at DATETIME DEFAULT \'NULL\'');
        $this->addSql('ALTER TABLE timesheet CHANGE nbr_of_bank_holidays nbr_of_bank_holidays INT DEFAULT NULL, CHANGE nbre_of_saturdays nbre_of_saturdays INT DEFAULT NULL, CHANGE nbre_of_sundays nbre_of_sundays INT DEFAULT NULL, CHANGE path path VARCHAR(255) CHARACTER SET utf8mb4 DEFAULT \'NULL\' COLLATE `utf8mb4_unicode_ci`, CHANGE updated_at updated_at DATETIME DEFAULT \'NULL\'');
        $this->addSql('ALTER TABLE user CHANGE contact_id contact_id INT DEFAULT NULL, CHANGE roles roles LONGTEXT CHARACTER SET utf8mb4 NOT NULL COLLATE `utf8mb4_bin`, CHANGE invoice invoice VARCHAR(255) CHARACTER SET utf8mb4 DEFAULT \'NULL\' COLLATE `utf8mb4_unicode_ci`, CHANGE updated_at updated_at DATETIME DEFAULT \'NULL\', CHANGE reset_token reset_token VARCHAR(255) CHARACTER SET utf8mb4 DEFAULT \'NULL\' COLLATE `utf8mb4_unicode_ci`');
    }
}
