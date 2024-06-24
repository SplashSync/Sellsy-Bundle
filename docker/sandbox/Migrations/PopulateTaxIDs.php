<?php

declare(strict_types=1);

/*
 *  This file is part of SplashSync Project.
 *
 *  Copyright (C) Splash Sync  <www.splashsync.com>
 *
 *  This program is distributed in the hope that it will be useful,
 *  but WITHOUT ANY WARRANTY; without even the implied warranty of
 *  MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.
 *
 *  For the full copyright and license information, please view the LICENSE
 *  file that was distributed with this source code.
 */

namespace DoctrineMigrations;

// phpcs:disable Generic.Files.LineLength

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class PopulateTaxIDs extends AbstractMigration
{
    public function getDescription(): string
    {
        return 'Sellsy Sandbox: Populate Default FR Tax IDs';
    }

    /**
     * @SuppressWarnings(PHPMD.UnusedFormalParameter)
     * @SuppressWarnings(PHPMD.ShortMethodName)
     */
    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        //        $this->addSql('DROP TABLE xml_listing');
        //        $this->addSql('ALTER TABLE estimation DROP pay_plan');
    }

    /**
     * @SuppressWarnings(PHPMD.UnusedFormalParameter)
     */
    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        //        $this->addSql('CREATE TABLE xml_listing (id INT AUTO_INCREMENT NOT NULL, generatedAt DATETIME NOT NULL, pushedAt DATETIME DEFAULT NULL, advertCount INT DEFAULT NULL, rawXml LONGTEXT CHARACTER SET utf8mb3 DEFAULT NULL COLLATE `utf8mb3_unicode_ci`, errors VARCHAR(1024) CHARACTER SET utf8mb3 DEFAULT NULL COLLATE `utf8mb3_unicode_ci`, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb3 COLLATE `utf8mb3_unicode_ci` ENGINE = InnoDB COMMENT = \'\' ');
        //        $this->addSql('ALTER TABLE estimation ADD pay_plan VARCHAR(255) DEFAULT NULL');
    }
}
