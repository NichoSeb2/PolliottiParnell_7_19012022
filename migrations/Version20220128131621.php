<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

final class Version20220128131621 extends AbstractMigration {
    public function getDescription(): string {
        return 'added new field to product and user entity';
    }

    public function up(Schema $schema): void {
        $this->addSql('ALTER TABLE product ADD description LONGTEXT NOT NULL, ADD color VARCHAR(255) NOT NULL');
        $this->addSql('ALTER TABLE user ADD last_name VARCHAR(255) NOT NULL, ADD email VARCHAR(255) NOT NULL, CHANGE name first_name VARCHAR(255) NOT NULL');
    }

    public function down(Schema $schema): void {
        $this->addSql('ALTER TABLE product DROP description, DROP color');
        $this->addSql('ALTER TABLE user ADD name VARCHAR(255) CHARACTER SET utf8mb4 NOT NULL COLLATE `utf8mb4_unicode_ci`, DROP first_name, DROP last_name, DROP email');
    }
}
