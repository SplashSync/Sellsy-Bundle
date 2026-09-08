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

namespace Splash\Connectors\Sellsy\Test\Controller;

use Exception;
use PHPUnit\Framework\Assert;
use Splash\Bundle\Phpunit\ConnectorTestCase;
use Splash\Connectors\Sellsy\Connector\SellsyConnector;

/**
 * Sellsy Connector Minimal Tests
 */
class S00MinimalObjectsTest extends ConnectorTestCase
{
    /**
     * Connector Server ID
     */
    const CONNECTOR = 'ThisIsSandBoxWsId';

    /**
     * Connector is loaded, and is the Sellsy one
     *
     * @throws Exception
     */
    public function testConnectorLoading(): void
    {
        Assert::assertInstanceOf(SellsyConnector::class, self::getConnector(self::CONNECTOR));
    }

    /**
     * Connector self test passes on its own configuration
     *
     * @throws Exception
     */
    public function testConnectorSelfTest(): void
    {
        $connector = self::getConnector(self::CONNECTOR);
        Assert::assertInstanceOf(SellsyConnector::class, $connector);
        Assert::assertTrue($connector->selfTest());
    }

    /**
     * Connector reaches the Sellsy Api
     *
     * @throws Exception
     */
    public function testConnectorConnect(): void
    {
        $connector = self::getConnector(self::CONNECTOR);
        Assert::assertInstanceOf(SellsyConnector::class, $connector);
        Assert::assertTrue($connector->connect());
    }

    /**
     * All expected Object Types are exposed
     *
     * @throws Exception
     */
    public function testConnectorObjectsTypes(): void
    {
        $connector = self::getConnector(self::CONNECTOR);
        Assert::assertInstanceOf(SellsyConnector::class, $connector);
        //====================================================================//
        // Every Object Type declared by the Connector must be available
        $objectTypes = $connector->getAvailableObjects();
        foreach (array("ThirdParty", "Address", "Product", "Invoice", "Webhook") as $objectType) {
            Assert::assertContains($objectType, $objectTypes);
        }
    }
}
