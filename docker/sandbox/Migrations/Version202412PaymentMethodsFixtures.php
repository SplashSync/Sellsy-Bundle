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
 * Populate Default Payment Methods
 *
 * Written as plain SQL: ContainerAwareInterface, which the previous version
 * used to reach the entity manager, was removed in Symfony 7.
 */
final class Version202412PaymentMethodsFixtures extends AbstractMigration
{
    /**
     * Sandbox Payment Methods
     *
     * @var string[]
     */
    private const METHODS = array(
        "JohnnyCash",
        "WalkerBank",
        "DrunkFood",
    );

    public function getDescription(): string
    {
        return 'Sellsy Sandbox: Populate Default Payment Methods';
    }

    /**
     * @SuppressWarnings(UnusedFormalParameter)
     * @SuppressWarnings(ShortMethodName)
     */
    public function up(Schema $schema): void
    {
        foreach (self::METHODS as $label) {
            $this->addSql(
                "INSERT INTO payment_method (label) VALUES (:label)",
                array("label" => $label)
            );
        }
    }

    /**
     * @SuppressWarnings(UnusedFormalParameter)
     */
    public function down(Schema $schema): void
    {
        $this->addSql("DELETE FROM payment_method");
    }
}
