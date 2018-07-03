<?php declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20180703112029 extends AbstractMigration
{
    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('CREATE TABLE role (id INT AUTO_INCREMENT NOT NULL, value VARCHAR(255) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE context (id INT AUTO_INCREMENT NOT NULL, value VARCHAR(255) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE = InnoDB');
        $this->addSql('ALTER TABLE project ADD role_id INT NOT NULL, ADD context_id INT NOT NULL, ADD summary LONGTEXT NOT NULL');
        $this->addSql('ALTER TABLE project ADD CONSTRAINT FK_2FB3D0EED60322AC FOREIGN KEY (role_id) REFERENCES role (id)');
        $this->addSql('ALTER TABLE project ADD CONSTRAINT FK_2FB3D0EE6B00C1CF FOREIGN KEY (context_id) REFERENCES context (id)');
        $this->addSql('CREATE INDEX IDX_2FB3D0EED60322AC ON project (role_id)');
        $this->addSql('CREATE INDEX IDX_2FB3D0EE6B00C1CF ON project (context_id)');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE project DROP FOREIGN KEY FK_2FB3D0EED60322AC');
        $this->addSql('ALTER TABLE project DROP FOREIGN KEY FK_2FB3D0EE6B00C1CF');
        $this->addSql('DROP TABLE role');
        $this->addSql('DROP TABLE context');
        $this->addSql('DROP INDEX IDX_2FB3D0EED60322AC ON project');
        $this->addSql('DROP INDEX IDX_2FB3D0EE6B00C1CF ON project');
        $this->addSql('ALTER TABLE project DROP role_id, DROP context_id, DROP summary');
    }
}
