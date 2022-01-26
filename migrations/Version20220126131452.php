<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

final class Version20220126131452 extends AbstractMigration {
    public function getDescription(): string {
        return 'added user-society relation';
    }

    public function up(Schema $schema): void {
        $this->addSql('ALTER TABLE user ADD society_id INT NOT NULL');
        $this->addSql('ALTER TABLE user ADD CONSTRAINT FK_8D93D649E6389D24 FOREIGN KEY (society_id) REFERENCES society (id)');
        $this->addSql('CREATE INDEX IDX_8D93D649E6389D24 ON user (society_id)');
    }

    public function down(Schema $schema): void {
        $this->addSql('ALTER TABLE user DROP FOREIGN KEY FK_8D93D649E6389D24');
        $this->addSql('DROP INDEX IDX_8D93D649E6389D24 ON user');
        $this->addSql('ALTER TABLE user DROP society_id');
    }
}
