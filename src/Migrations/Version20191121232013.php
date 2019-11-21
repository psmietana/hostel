<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20191121232013 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('CREATE TABLE app_reservations (id INT AUTO_INCREMENT NOT NULL, reserved_slots_number INT NOT NULL, date_from DATE NOT NULL, date_to DATE NOT NULL, allowed_multiple_flats TINYINT(1) DEFAULT \'0\' NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE app_flats (id INT AUTO_INCREMENT NOT NULL, code VARCHAR(15) NOT NULL, slots_number INT NOT NULL, discount_above_seven_days INT UNSIGNED DEFAULT NULL, UNIQUE INDEX UNIQ_BC398DE577153098 (code), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE app_flats_reservations (id INT AUTO_INCREMENT NOT NULL, reservation_id INT DEFAULT NULL, flat_id INT DEFAULT NULL, reserved_slots_number INT NOT NULL, INDEX IDX_D025FCEAB83297E7 (reservation_id), INDEX IDX_D025FCEAD3331C94 (flat_id), UNIQUE INDEX unique_idx (reservation_id, flat_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE app_flats_reservations ADD CONSTRAINT FK_D025FCEAB83297E7 FOREIGN KEY (reservation_id) REFERENCES app_reservations (id)');
        $this->addSql('ALTER TABLE app_flats_reservations ADD CONSTRAINT FK_D025FCEAD3331C94 FOREIGN KEY (flat_id) REFERENCES app_flats (id)');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE app_flats_reservations DROP FOREIGN KEY FK_D025FCEAB83297E7');
        $this->addSql('ALTER TABLE app_flats_reservations DROP FOREIGN KEY FK_D025FCEAD3331C94');
        $this->addSql('DROP TABLE app_reservations');
        $this->addSql('DROP TABLE app_flats');
        $this->addSql('DROP TABLE app_flats_reservations');
    }
}
