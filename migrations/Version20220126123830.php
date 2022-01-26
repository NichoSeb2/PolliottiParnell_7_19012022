<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

final class Version20220126123830 extends AbstractMigration {
    public function getDescription(): string {
        return 'added price field on product entity';
    }

    public function up(Schema $schema): void {
        $this->addSql('ALTER TABLE product ADD price DOUBLE PRECISION NOT NULL');
    }

    public function down(Schema $schema): void {
        $this->addSql('ALTER TABLE product DROP price');
    }
}
