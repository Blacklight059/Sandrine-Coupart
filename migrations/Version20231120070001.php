<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20231120070001 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE comment (id INT AUTO_INCREMENT NOT NULL, recipe_id INT DEFAULT NULL, user_id INT DEFAULT NULL, comment LONGTEXT NOT NULL, date DATETIME NOT NULL, INDEX IDX_9474526C59D8A214 (recipe_id), INDEX IDX_9474526CA76ED395 (user_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE comment ADD CONSTRAINT FK_9474526C59D8A214 FOREIGN KEY (recipe_id) REFERENCES recipe (id)');
        $this->addSql('ALTER TABLE comment ADD CONSTRAINT FK_9474526CA76ED395 FOREIGN KEY (user_id) REFERENCES user (id)');
        $this->addSql('ALTER TABLE `admin` CHANGE roles roles JSON NOT NULL');
        $this->addSql('ALTER TABLE allergens_recipe DROP FOREIGN KEY FK_4754DA66711662F1');
        $this->addSql('ALTER TABLE allergens_recipe ADD CONSTRAINT FK_4754DA66711662F1 FOREIGN KEY (allergens_id) REFERENCES allergens (id)');
        $this->addSql('ALTER TABLE diet_types_recipe DROP FOREIGN KEY FK_5F2EAF60EC9FDDD2');
        $this->addSql('ALTER TABLE diet_types_recipe ADD CONSTRAINT FK_5F2EAF60EC9FDDD2 FOREIGN KEY (diet_types_id) REFERENCES diet_types (id)');
        $this->addSql('ALTER TABLE user CHANGE roles roles JSON NOT NULL');
        $this->addSql('ALTER TABLE messenger_messages CHANGE delivered_at delivered_at DATETIME DEFAULT NULL COMMENT \'(DC2Type:datetime_immutable)\'');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE comment DROP FOREIGN KEY FK_9474526C59D8A214');
        $this->addSql('ALTER TABLE comment DROP FOREIGN KEY FK_9474526CA76ED395');
        $this->addSql('DROP TABLE comment');
        $this->addSql('ALTER TABLE allergens_recipe DROP FOREIGN KEY FK_4754DA66711662F1');
        $this->addSql('ALTER TABLE allergens_recipe ADD CONSTRAINT FK_4754DA66711662F1 FOREIGN KEY (allergens_id) REFERENCES allergens (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE `admin` CHANGE roles roles LONGTEXT NOT NULL COLLATE `utf8mb4_bin`');
        $this->addSql('ALTER TABLE user CHANGE roles roles LONGTEXT NOT NULL COLLATE `utf8mb4_bin`');
        $this->addSql('ALTER TABLE messenger_messages CHANGE delivered_at delivered_at DATETIME DEFAULT \'NULL\' COMMENT \'(DC2Type:datetime_immutable)\'');
        $this->addSql('ALTER TABLE diet_types_recipe DROP FOREIGN KEY FK_5F2EAF60EC9FDDD2');
        $this->addSql('ALTER TABLE diet_types_recipe ADD CONSTRAINT FK_5F2EAF60EC9FDDD2 FOREIGN KEY (diet_types_id) REFERENCES diet_types (id) ON DELETE CASCADE');
    }
}
