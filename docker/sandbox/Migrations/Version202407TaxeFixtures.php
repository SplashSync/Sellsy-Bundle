<?php

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

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Populate Default FR Tax IDs
 *
 * Written as plain SQL: ContainerAwareInterface, which the previous version
 * used to reach the entity manager, was removed in Symfony 7.
 */
final class Version202407TaxeFixtures extends AbstractMigration
{
    /**
     * Default French VAT rates
     *
     * @var array<string, float>
     */
    private const TAXES = array(
        "VATFR20" => 20.0,
        "VATFR10" => 10.0,
        "VATFR55" => 5.5,
        "VATFR0" => 0.0,
    );

    public function getDescription(): string
    {
        return 'Sellsy Sandbox: Populate Default FR Tax IDs';
    }

    /**
     * @SuppressWarnings(UnusedFormalParameter)
     * @SuppressWarnings(ShortMethodName)
     */
    public function up(Schema $schema): void
    {
        foreach (self::TAXES as $label => $rate) {
            $this->addSql(
                "INSERT INTO taxe (rate, label, is_active, is_ecotax) VALUES (:rate, :label, 1, 0)",
                array("rate" => $rate, "label" => $label)
            );
        }
    }

    /**
     * @SuppressWarnings(UnusedFormalParameter)
     */
    public function down(Schema $schema): void
    {
        $this->addSql("DELETE FROM taxe");
    }
}
